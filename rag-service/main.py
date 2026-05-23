from fastapi import FastAPI, UploadFile, File, Form
from fastapi.responses import JSONResponse
import fitz  # PyMuPDF
import chromadb
from sentence_transformers import SentenceTransformer
import httpx, json, re, traceback

app = FastAPI()
model = SentenceTransformer("BAAI/bge-m3")
client = chromadb.PersistentClient(path="./db")
collection = client.get_or_create_collection("courses")
OLLAMA_URL = "http://127.0.0.1:11434/api/generate"
OLLAMA_MODEL = "llama3.2:3b"

def extract_text(pdf_bytes: bytes) -> str:
    doc = fitz.open(stream=pdf_bytes, filetype="pdf")
    return "\n".join(page.get_text() for page in doc)

def chunk_with_overlap(text: str, size=800, overlap=150) -> list[str]:
    if not text:
        return []
    words = text.split()
    chunks, i = [], 0
    while i < len(words):
        chunk = " ".join(words[i:i+size])
        if chunk.strip():
            chunks.append(chunk)
        i += size - overlap
    return chunks

def embed(texts: list[str]) -> list[list[float]]:
    sanitized = [str(t) if t is not None else "" for t in texts]
    if not sanitized:
        return []
    return model.encode(sanitized, batch_size=16).tolist()

async def call_ollama(prompt: str) -> str:
    async with httpx.AsyncClient(timeout=600) as c:
        try:
            r = await c.post(OLLAMA_URL,
                json={"model": OLLAMA_MODEL, "prompt": prompt, "stream": False})
            r.raise_for_status()
            body = r.json()
            return body.get("response", "")
        except Exception as e:
            raise RuntimeError(f"Ollama error: {e}")


def extract_json(raw: str) -> dict:
    # strip markdown fences
    cleaned = re.sub(r"```json|```", "", raw).strip()

    # try direct parse first
    try:
        return json.loads(cleaned)
    except json.JSONDecodeError:
        pass

    # try to find JSON object inside the text
    match = re.search(r'\{.*\}', cleaned, re.DOTALL)
    if match:
        try:
            return json.loads(match.group())
        except json.JSONDecodeError:
            pass

    # return a safe fallback so it never 500s
    return {
        "title": "Résumé généré",
        "overview": cleaned[:500] if cleaned else "Aucun contenu extrait.",
        "chapters": [],
        "formulas": [],
        "key_terms": {},
        "difficulty": "intermediate"
    }

SCHEMA = """{"title":"...","overview":"2-3 sentences","chapters":[{"title":"...","key_concepts":["..."],"summary":"..."}],"formulas":["..."],"key_terms":{"term":"definition"},"difficulty":"beginner|intermediate|advanced"}"""

def build_prompt(chunks: list[str]) -> str:
    context = "\n\n---\n\n".join(chunks)
    return f"""You are an academic summarizer. Respond ONLY with valid JSON matching this schema. No markdown, no explanation.

Schema: {SCHEMA}

Content:
{context}

JSON:"""

@app.post("/process")
async def process_pdf(
    file: UploadFile = File(...),
    doc_id: str = Form(...),
    query: str = Form(default="summarize this document")
):
    pdf_bytes = await file.read()
    text = extract_text(pdf_bytes)
    if not text or not text.strip():
        return JSONResponse(status_code=422, content={"error": "PDF text extraction failed or document contains no extractable text."})

    chunks = chunk_with_overlap(text)
    if not chunks:
        return JSONResponse(status_code=422, content={"error": "Document produced no text chunks for embedding."})

    embeddings = embed(chunks)
    if not embeddings:
        return JSONResponse(status_code=422, content={"error": "Embedding generation failed for document chunks."})

    existing = collection.get(where={"doc_id": doc_id})
    if not existing.get("ids"):
        collection.add(
            documents=chunks,
            embeddings=embeddings,
            ids=[f"{doc_id}_{i}" for i in range(len(chunks))],
            metadatas=[{"doc_id": doc_id} for _ in chunks]
        )

    query_vecs = embed([query])
    if not query_vecs:
        return JSONResponse(status_code=422, content={"error": "Query embedding failed."})
    query_vec = query_vecs[0]

    results = collection.query(
        query_embeddings=[query_vec],
        n_results=8,
        where={"doc_id": doc_id}
    )
    if not results or not results.get("documents") or not results["documents"][0]:
        return JSONResponse(status_code=404, content={"error": "No relevant content found for this document."})
    relevant = results["documents"][0]

    try:
        raw = await call_ollama(build_prompt(relevant))
        return extract_json(raw)
    except Exception as e:
        traceback.print_exc()
        return JSONResponse(status_code=502, content={"error": str(e)})


@app.post('/query')
async def query_doc(payload: dict):
    doc_id = payload.get('doc_id')
    query = payload.get('query', 'summarize this document')
    if not doc_id:
        return JSONResponse(status_code=400, content={"error": "doc_id required"})

    query_vecs = embed([query])
    if not query_vecs:
        return JSONResponse(status_code=422, content={"error": "Query embedding failed."})
    query_vec = query_vecs[0]

    results = collection.query(
        query_embeddings=[query_vec],
        n_results=8,
        where={"doc_id": doc_id}
    )
    if not results or not results.get('documents') or not results['documents'][0]:
        return JSONResponse(status_code=404, content={"error": "No documents found for this doc_id"})

    relevant = results['documents'][0]
    try:
        raw = await call_ollama(build_prompt(relevant))
        return extract_json(raw)
    except Exception as e:
        traceback.print_exc()
        return JSONResponse(status_code=502, content={"error": str(e)})
