@extends('layouts.app')

@section('title', 'Rejoindre un Cours')
@section('page-title', 'Rejoindre un Cours')

@section('extra-styles')
<style>
    .join-container {
        max-width: 500px;
        margin: 0 auto;
        background: #0f172a;
        padding: 3rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        text-align: center;
        border: 1px solid #334155;
    }
    .icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    .subtitle {
        color: #cbd5e1;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }
    .info-box {
        background: rgba(99,102,241,0.1);
        border-left: 4px solid #6366f1;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        font-size: 0.9rem;
        text-align: left;
        color: #a5b4fc;
    }
    .form-group {
        margin-bottom: 1.5rem;
        text-align: left;
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
        font-family: monospace;
        text-align: center;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        background: #1e293b;
        color: #e2e8f0;
    }
    input:focus {
        outline: none;
        border-color: #6366f1;
    }
    .example {
        text-align: center;
        color: #64748b;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        font-family: monospace;
    }
    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        text-align: center;
    }
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn {
        padding: 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        display: inline-block;
        flex: 1;
        text-align: center;
        font-weight: bold;
    }
    .btn-primary {
        background-color: #4f46e5;
        color: white;
    }
    .btn-primary:hover {
        background-color: #4338ca;
    }
    .btn-secondary {
        background-color: #475569;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #334155;
    }
</style>
@endsection

@section('content')
    <div class="join-container">
        <div class="icon">🎓</div>
        <p class="subtitle">Entrez le code fourni par votre enseignant</p>

        <div class="info-box">
            ℹ️ Demandez à votre enseignant le code d'accès du cours pour vous inscrire.
        </div>

        <form method="POST" action="{{ route('student.join-course') }}">
            @csrf

            <div class="form-group">
                <label for="join_code">Code du Cours</label>
                <input type="text"
                       id="join_code"
                       name="join_code"
                       placeholder="ABC-XYZ-123"
                       maxlength="15"
                       value="{{ old('join_code') }}"
                       required
                       autofocus>
                <div class="example">Format: XXX-XXX-123</div>
                @error('join_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Rejoindre le Cours
                </button>
                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                    ✗ Annuler
                </a>
            </div>
        </form>
    </div>
@endsection

@section('extra-scripts')
<script>
    document.getElementById('join_code').addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (value.length > 9) {
            value = value.substring(0, 9);
        }
        if (value.length > 6) {
            value = value.substring(0, 3) + '-' + value.substring(3, 6) + '-' + value.substring(6);
        } else if (value.length > 3) {
            value = value.substring(0, 3) + '-' + value.substring(3);
        }
        e.target.value = value;
    });
</script>
@endsection