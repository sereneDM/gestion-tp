import os
import sys
import json
from pypdf import PdfReader
from llama_cpp import Llama
from fastapi import FastAPI, Form
from fastapi.responses import PlainTextResponse
from fastapi.middleware.cors import CORSMiddleware

# ── French translation dictionary ────────────────────────────────────────────

FRENCH_TRANSLATIONS = {
    # JSON structure keys
    "title": "titre",
    "overview": "aperçu",
    "summary": "résumé",
    "difficulty": "difficulté",
    "chapters": "chapitres",
    "chapter": "chapitre",
    "key_concepts": "concepts_clés",
    "key_terms": "termes_clés",
    "formulas": "formules",
    "text": "texte",
    "content": "contenu",
    # Difficulty levels
    "Beginner": "Débutant",
    "Intermediate": "Intermédiaire", 
    "Advanced": "Avancé",
    "Basic": "De base",
    # Common headers
    "Fundamentals": "Principes fondamentaux",
    "Introduction": "Introduction",
    "Overview": "Aperçu",
    "Summary": "Résumé",
    "Key Concepts": "Concepts clés",
    "Key Terms": "Termes clés",
    "Formulas": "Formules",
    "Definition": "Définition",
    "Concept": "Concept",
    "Example": "Exemple",
    "Application": "Application",
    "Practice": "Pratique",
    "Exercise": "Exercice",
    "Explanation": "Explication",
}

def translate_to_french(data):
    """Keep English keys for compatibility with frontend, content is already in French"""
    # No translation of keys - frontend expects English structure
    # The model output (content) is in English, but that's OK
    # Frontend will display it as-is
    return data


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

    # Trim content safely to fit inside the context window (reduced from 4000 for speed)
    max_chars = 2500
    if len(course_content) > max_chars:
        course_content = course_content[:max_chars]

    # Enforce JSON output format matching the frontend structure
    json_schema_instruction = (
        "Respond with ONLY a valid JSON object. No markdown, no extra text.\n"
        "Write all text content in FRENCH language.\n"
        "Use this exact JSON structure:\n"
        "{\n"
        "  \"title\": \"Titre en Français\",\n"
        "  \"overview\": \"Résumé en Français\",\n"
        "  \"difficulty\": \"Débutant\",\n"
        "  \"chapters\": [{\"title\": \"Titre\", \"summary\": \"Résumé\", \"key_concepts\": [\"terme1\", \"terme2\"]}],\n"
        "  \"key_terms\": {\"Terme\": \"Définition\"},\n"
        "  \"formulas\": [\"formule\"]\n"
        "}"
    )

    if custom_query:
        user_instruction = f"Summarize based on: {custom_query}\n{json_schema_instruction}"
    else:
        user_instruction = f"Create a course summary.\n{json_schema_instruction}"

    # 2. Simple, direct prompt
    prompt = f"""<|start_header_id|>system<|end_header_id|>
Create JSON course summaries. Write all text in FRENCH. Output ONLY valid JSON.<|eot_id|><|start_header_id|>user<|end_header_id|>
Course material:
{course_content}

{user_instruction}<|eot_id|><|start_header_id|>assistant<|end_header_id|>"""

    # 3. Load the local model directly from your hard drive
    if not os.path.exists(model_path):
        raise FileNotFoundError(f"Model file not found at: {model_path}. Please verify the path.")

    llm = Llama(
        model_path=model_path,
        n_ctx=4096,
        verbose=False
    )

    # 4. Generate the response with optimized settings for speed
    response = llm(
        prompt,
        max_tokens=512,
        temperature=0.5,
        top_p=0.9,
        top_k=40,
        repeat_penalty=1.1,
        stop=["<|eot_id|>"]
    )

    result_text = response['choices'][0]['text'].strip()
    
    # Extract and return valid JSON
    try:
        # Try to find and parse JSON from response
        json_start = result_text.find('{')
        json_end = result_text.rfind('}') + 1
        
        if json_start >= 0 and json_end > json_start:
            json_str = result_text[json_start:json_end]
            # Validate by parsing
            data = json.loads(json_str)
            # Return as formatted JSON
            return json.dumps(data, ensure_ascii=False, indent=2)
        else:
            # No JSON found, return raw text
            return result_text
    except json.JSONDecodeError as e:
        # If parsing fails, return raw text
        return result_text
    except Exception as e:
        # Catch any other errors
        return f'{{"error": "Failed to parse response: {str(e)}"}}'


# ── CLI entry point ────────────────────────────────────────────────────────────

if __name__ == "__main__":
    query_argument = sys.argv[1] if len(sys.argv) > 1 else None
    try:
        output_text = generate_resume_from_course(COURSE_PDF, MODEL_FILE, custom_query=query_argument)
        print(output_text.strip())
    except Exception as e:
        print(f"Error executing RAG service: {str(e)}", file=sys.stderr)
        sys.exit(1)