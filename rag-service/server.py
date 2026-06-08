from fastapi import FastAPI, UploadFile, File, Form, Request
from fastapi.responses import JSONResponse
from fastapi.concurrency import run_in_threadpool
import tempfile, os, json, hashlib
from main import extract_text_from_pdf, generate_resume_from_course

app = FastAPI()

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_FILE = os.path.join(BASE_DIR, "models", "llama-3.2-3b-instruct-q4_k_m.gguf")
STORE_FILE = os.path.join(BASE_DIR, "doc_store.json")
CACHE_FILE = os.path.join(BASE_DIR, "results_cache.json")


def load_store():
    if os.path.exists(STORE_FILE):
        with open(STORE_FILE, 'r') as f:
            return json.load(f)
    return {}


def save_store(store):
    with open(STORE_FILE, 'w') as f:
        json.dump(store, f)


def load_cache():
    if os.path.exists(CACHE_FILE):
        try:
            with open(CACHE_FILE, 'r', encoding='utf-8') as f:
                return json.load(f)
        except:
            return {}
    return {}


def save_cache(cache):
    with open(CACHE_FILE, 'w', encoding='utf-8') as f:
        json.dump(cache, f)


doc_store = load_store()
results_cache = load_cache()


@app.get("/health")
async def health():
    return {"status": "ok", "service": "rag-service"}


@app.post("/ingest")
async def ingest(
    file: UploadFile = File(...),
    doc_id: str = Form(...),
    query: str = Form("")
):
    with tempfile.NamedTemporaryFile(delete=False, suffix=".pdf") as tmp:
        tmp.write(await file.read())
        tmp_path = tmp.name

    try:
        # Run blocking PDF extraction in thread pool
        text = await run_in_threadpool(extract_text_from_pdf, tmp_path)
        doc_store[doc_id] = text
        save_store(doc_store)
        return {"result": "ok", "doc_id": doc_id}
    except Exception as e:
        return JSONResponse(status_code=500, content={"error": str(e)})
    finally:
        if os.path.exists(tmp_path):
            os.unlink(tmp_path)


@app.post("/process")
async def process(
    file: UploadFile = File(...),
    doc_id: str = Form(...),
    query: str = Form("summarize this document")
):
    with tempfile.NamedTemporaryFile(delete=False, suffix=".pdf") as tmp:
        tmp.write(await file.read())
        tmp_path = tmp.name

    try:
        # Check cache first
        cache_key = f"{doc_id}:{query}"
        if cache_key in results_cache:
            return {"result": results_cache[cache_key], "cached": True, "doc_id": doc_id}

        # Run blocking PDF extraction in thread pool
        text = await run_in_threadpool(extract_text_from_pdf, tmp_path)
        doc_store[doc_id] = text
        save_store(doc_store)

        # Run blocking Llama inference in thread pool — prevents event loop blocking
        result = await run_in_threadpool(
            generate_resume_from_course, tmp_path, MODEL_FILE, query
        )
        # Cache the result
        results_cache[cache_key] = result
        save_cache(results_cache)
        return {"result": result, "doc_id": doc_id}
    except FileNotFoundError as e:
        return JSONResponse(status_code=404, content={"error": str(e)})
    except Exception as e:
        return JSONResponse(status_code=500, content={"error": str(e)})
    finally:
        if os.path.exists(tmp_path):
            os.unlink(tmp_path)


@app.post("/query")
async def query_doc(request: Request):
    try:
        data = await request.json()
    except:
        return JSONResponse(status_code=400, content={"error": "Invalid JSON in request body"})
    
    doc_id = data.get("doc_id")
    query = data.get("query", "summarize")

    if not doc_id:
        return JSONResponse(status_code=400, content={"error": "doc_id is required"})

    # Check cache first
    cache_key = f"{doc_id}:{query}"
    if cache_key in results_cache:
        return {"result": results_cache[cache_key], "cached": True, "doc_id": doc_id}

    if doc_id not in doc_store:
        return JSONResponse(status_code=404, content={"error": f"doc_id '{doc_id}' not found"})

    with tempfile.NamedTemporaryFile(delete=False, suffix=".txt", mode='w', encoding='utf-8') as tmp:
        tmp.write(doc_store[doc_id])
        tmp_path = tmp.name

    try:
        # Run blocking Llama inference in thread pool
        result = await run_in_threadpool(
            generate_resume_from_course, tmp_path, MODEL_FILE, query
        )
        # Cache the result
        results_cache[cache_key] = result
        save_cache(results_cache)
        return {"result": result, "doc_id": doc_id}
    except Exception as e:
        return JSONResponse(status_code=500, content={"error": str(e)})
    finally:
        if os.path.exists(tmp_path):
            os.unlink(tmp_path)