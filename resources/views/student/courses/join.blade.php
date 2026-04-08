@extends('layouts.student')

@section('title', 'Rejoindre un cours')
@section('page-title', 'Rejoindre un Cours')

@section('extra-styles')
<style>
    .join-container {
        max-width: 500px;
        margin: 2rem auto;
        background: white;
        padding: 3rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
        color: #333;
        font-weight: bold;
    }
    input {
        width: 100%;
        padding: 1rem;
        border: 2px solid #ddd;
        border-radius: 4px;
        font-size: 1.2rem;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        font-family: monospace;
    }
    input:focus {
        outline: none;
        border-color: #007bff;
    }
    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        text-align: center;
    }
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #007bff;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .btn {
        width: 100%;
        padding: 1rem;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn:hover {
        background: #0056b3;
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