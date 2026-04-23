@extends('layouts.app')

@section('title', 'Rejoindre un cours')
@section('page-title', 'Rejoindre un Cours')

@section('extra-styles')
<style>
    .join-container {
        max-width: 500px;
        margin: 2rem auto;
        background: #0f172a;
        padding: 3rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        border: 1px solid #334155;
    }
    .icon {
        text-align: center;
        font-size: 5rem;
        margin-bottom: 1rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #e2e8f0;
        font-weight: bold;
    }
    input {
        width: 100%;
        padding: 1rem;
        border: 2px solid #475569;
        border-radius: 4px;
        font-size: 1.2rem;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        font-family: monospace;
        background: #1e293b;
        color: #e2e8f0;
    }
    input:focus {
        outline: none;
        border-color: #6366f1;
    }
    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        text-align: center;
    }
    .info-box {
        background: rgba(99,102,241,0.1);
        border-left: 4px solid #6366f1;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #a5b4fc;
    }
    .btn {
        width: 100%;
        padding: 1rem;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn:hover {
        background: #4338ca;
    }
</style>
@endsection

@section('content')
    <div class="join-container">
        <div class="icon">🎓</div>
        <h2 style="text-align: center; margin-bottom: 1rem;">Rejoindre un Cours</h2>
        
        <div class="info-box">
           ℹ️ Entrez le code d'accès fourni par votre enseignant (format: XXX-XXX-123)
        </div>

        <form method="POST" action="{{ route('student.join-course') }}">
            @csrf
            
            <div class="form-group">
                <label for="join_code">Code d'accès</label>
                <input type="text" 
                       id="join_code" 
                       name="join_code" 
                       value="{{ old('join_code') }}"
                       placeholder="EQY-ZIH-439"
maxlength="11"
                       required
                       autofocus>
                @error('join_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn">
                ✓ Rejoindre le cours
            </button>
        </form>
    </div>
@endsection