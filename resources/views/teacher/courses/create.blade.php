@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.courses.create') }}
@endsection

@section('title', 'Créer un Cours')
@section('page-title', 'Créer un Nouveau Cours')

@section('extra-styles')
<style>
    .form-container {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        border: 1px solid #334155;
        max-width: 800px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #cbd5e1;
        font-weight: bold;
    }
    input, textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #475569;
        border-radius: 0.75rem;
        font-size: 1rem;
        font-family: Arial, sans-serif;
        background: #1e293b;
        color: #e2e8f0;
    }
    textarea {
        min-height: 120px;
        resize: vertical;
    }
    input::placeholder, textarea::placeholder {
        color: #94a3b8;
    }
    input:focus, textarea:focus {
        outline: none;
        border-color: #6366f1;
    }
    .error {
        color: #fca5a5;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .info-box {
        background: #0f172a;
        border-left: 4px solid #4f46e5;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0.75rem;
        color: #cbd5e1;
    }
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        display: inline-block;
        flex: 1;
        text-align: center;
        color: #e2e8f0;
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
    .char-counter { text-align: right; font-size: 0.78rem; margin-top: 0.25rem; color: #64748b; transition: color 0.2s; }
.char-counter.warning { color: #f59e0b; }
.char-counter.danger  { color: #ef4444; }
</style>
@endsection

@section('content')
    <div class="form-container">
        <div class="info-box">
            ℹ️ Un code unique sera automatiquement généré pour permettre à vos étudiants de rejoindre ce cours.
        </div>

        <form method="POST" action="{{ route('teacher.courses.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nom du cours *</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Ex: Programmation Web Avancée"
                       maxlength="50"
                       required>
                       <div class="char-counter" id="name-counter">0 / 50</div>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description"
                          placeholder="Décrivez brièvement ce cours...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Créer le cours
                </button>
                <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary">
    ✗ Annuler
</a>
            </div>
        </form>
    </div>
<script>
    const nameInput   = document.getElementById('name');
    const nameCounter = document.getElementById('name-counter');
    const maxLength   = 50;

    function updateCounter() {
        const len = nameInput.value.length;
        nameCounter.textContent = len + ' / ' + maxLength;
        nameCounter.classList.remove('warning', 'danger');
        if (len >= maxLength)             nameCounter.classList.add('danger');
        else if (len >= maxLength * 0.8)  nameCounter.classList.add('warning');
    }
    nameInput.addEventListener('input', updateCounter);
    updateCounter();
</script>
@endsection
