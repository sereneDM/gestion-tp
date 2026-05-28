import os
import sys
from pypdf import PdfReader
from llama_cpp import Llama
from fastapi import FastAPI, Form
from fastapi.responses import PlainTextResponse
from fastapi.middleware.cors import CORSMiddleware

# ── FastAPI app ────────────────────────────────────────────────────────────────

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_FILE = os.path.join(BASE_DIR, "models", "llama-3.2-3b-instruct-q4_k_m.gguf")
COURSE_PDF = os.path.join(BASE_DIR, "test_sample.pdf")

@app.get("/health")
def health():
    return {"status": "ok"}

@app.post("/generate", response_class=PlainTextResponse)
async def generate(query: str = Form(None)):
    try:
        result = generate_resume_from_course(COURSE_PDF, MODEL_FILE, custom_query=query)
        return result.strip()
    except Exception as e:
        return f"Error: {str(e)}"

# ── Core logic ─────────────────────────────────────────────────────────────────

def extract_text_from_pdf(pdf_path):
    """Extracts text from all pages of the target PDF file, or reads it as plain text if it is a text file."""
    if not os.path.exists(pdf_path):
        raise FileNotFoundError(f"Could not find the file at: {pdf_path}")

    with open(pdf_path, 'rb') as f:
        header = f.read(4)

    if header == b'%PDF':
        reader = PdfReader(pdf_path)
        full_text = ""
        for page in reader.pages:
            text = page.extract_text()
            if text:
                full_text += text + "\n"
        return full_text
    else:
        with open(pdf_path, 'r', encoding='utf-8', errors='ignore') as f:
            return f.read()


def generate_resume_from_course(pdf_path, model_path, custom_query=None):
    # 1. Extract content from your course PDF
    course_content = extract_text_from_pdf(pdf_path)

    # Trim content safely to fit inside the context window
    max_chars = 4000
    if len(course_content) > max_chars:
        course_content = course_content[:max_chars]

    # Enforce JSON output format matching the frontend structure
    json_schema_instruction = (
        "You MUST respond with a valid JSON object ONLY. Do NOT wrap your response in markdown code blocks "
        "(such as ```json or ```). Do NOT include any conversational intro/outro text. The response must be "
        "a valid JSON parseable string.\n\n"
        "The JSON object MUST strictly follow this schema:\n"
        "{\n"
        "  \"title\": \"Course Title / Topic Title\",\n"
        "  \"overview\": \"An elegant overview or summary explaining the content or answering the query\",\n"
        "  \"difficulty\": \"Beginner, Intermediate, or Advanced\",\n"
        "  \"chapters\": [\n"
        "    {\n"
        "      \"title\": \"Chapter or Topic Name\",\n"
        "      \"summary\": \"Key summary of this chapter or topic\",\n"
        "      \"key_concepts\": [\"concept 1\", \"concept 2\"]\n"
        "    }\n"
        "  ],\n"
        "  \"key_terms\": {\n"
        "    \"Term\": \"Definition/explanation\"\n"
        "  },\n"
        "  \"formulas\": [\n"
        "    \"Formula, code snippet, or key expression\"\n"
        "  ]\n"
        "}"
    )

    if custom_query:
        user_instruction = f"Based on the course material below, answer this request: {custom_query}\n\n{json_schema_instruction}"
    else:
        user_instruction = f"Please generate a comprehensive course summary based on the course text.\n\n{json_schema_instruction}"

    # 2. Structure the prompt cleanly for Llama 3.2 Instruct
    prompt = f"""<|start_header_id|>system<|end_header_id|>
You are a professional assistant specialized in analyzing course materials and structuring course summaries as JSON.<|eot_id|><|start_header_id|>user<|end_header_id|>
Here is the course material:
---
{course_content}
---
{user_instruction}<|eot_id|><|start_header_id|>assistant<|end_header_id|>"""

    # 3. Load the local model directly from your hard drive
    if not os.path.exists(model_path):
        raise FileNotFoundError(f"Model file not found at: {model_path}. Please verify the path.")

    llm = Llama(
        model_path=model_path,
        n_ctx=4096,
        verbose=False
    )

    # 4. Generate the response
    response = llm(
        prompt,
        max_tokens=1024,
        temperature=0.3,
        stop=["<|eot_id|>"]
    )

    return response['choices'][0]['text']


# ── CLI entry point ────────────────────────────────────────────────────────────

if __name__ == "__main__":
    query_argument = sys.argv[1] if len(sys.argv) > 1 else None
    try:
        output_text = generate_resume_from_course(COURSE_PDF, MODEL_FILE, custom_query=query_argument)
        print(output_text.strip())
    except Exception as e:
        print(f"Error executing RAG service: {str(e)}", file=sys.stderr)
        sys.exit(1)