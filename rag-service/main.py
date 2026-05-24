import os
import sys
from pypdf import PdfReader
from llama_cpp import Llama

def extract_text_from_pdf(pdf_path):
    """Extracts text from all pages of the target PDF file."""
    if not os.path.exists(pdf_path):
        raise FileNotFoundError(f"Could not find the PDF at: {pdf_path}")
        
    reader = PdfReader(pdf_path)
    full_text = ""
    for page in reader.pages:
        text = page.extract_text()
        if text:
            full_text += text + "\n"
    return full_text

def generate_resume_from_course(pdf_path, model_path, custom_query=None):
    # 1. Extract content from your course PDF
    course_content = extract_text_from_pdf(pdf_path)
    
    # Trim content safely to fit inside the context window
    max_chars = 4000
    if len(course_content) > max_chars:
        course_content = course_content[:max_chars]

    # Adjust the instructions if Laravel passes a specific question/request
    if custom_query:
        user_instruction = f"Based on the course material below, answer this request: {custom_query}"
    else:
        user_instruction = "Please generate a professional resume section (Summary, Technical Skills, and Projects) based ONLY on the skills and knowledge taught in this course text."

    # 2. Structure the prompt cleanly for Llama 3.2 Instruct
    prompt = f"""<|start_header_id|>system<|end_header_id|>
You are a professional assistant specialized in analyzing course materials and structuring resumes.<|eot_id|><|start_header_id|>user<|end_header_id|>

Here is the course material:
---
{course_content}
---

{user_instruction}<|eot_id|><|start_header_id|>assistant<|end_header_id|>"""

    # 3. Load the local model directly from your hard drive
    if not os.path.exists(model_path):
        raise FileNotFoundError(f"Model file not found at: {model_path}. Please verify the path.")
        
    # n_ctx sets the token context window.
    # Note: If you ever want GPU acceleration later, add: n_gpu_layers=-1
    llm = Llama(
        model_path=model_path,
        n_ctx=2048,
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

if __name__ == "__main__":
    # Get the exact absolute directory of this main.py file
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))
    
    # Dynamically build absolute paths so Laravel can never lose them
    MODEL_FILE = os.path.join(BASE_DIR, "models", "llama-3.2-3b-instruct-q4_k_m.gguf")
    COURSE_PDF = os.path.join(BASE_DIR, "test_sample.pdf")
    
    # Capture any string sent from Laravel/CLI argument
    query_argument = sys.argv[1] if len(sys.argv) > 1 else None

    try:
        output_text = generate_resume_from_course(COURSE_PDF, MODEL_FILE, custom_query=query_argument)
        
        # Print the final result cleanly so Laravel can capture the stream
        print(output_text.strip())
        
    except Exception as e:
        # Crucial for debugging: this will show up in your Laravel logs
        print(f"Error executing RAG service: {str(e)}", file=sys.stderr)
        sys.exit(1)