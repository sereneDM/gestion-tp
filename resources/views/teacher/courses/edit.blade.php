@extends('layouts.app')

@section('title', 'Modifier le Cours')
@section('page-title', 'Modifier le Cours')

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
    input, textarea, select {
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
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #6366f1;
    }
    .error {
        color: #fca5a5;
        font-size: 0.875rem;
        margin-top: 0.25rem;
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
</style>
@endsection

@section('content')
    <div class="form-container">
        <form method="POST" action="{{ route('teacher.courses.update', $course->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nom du cours *</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $course->name) }}"
                       required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Statut *</label>
                <select id="status" name="status" required>
                    <option value="active" {{ old('status', $course->status) === 'active' ? 'selected' : '' }}>
                        Actif
                    </option>
                    <option value="archived" {{ old('status', $course->status) === 'archived' ? 'selected' : '' }}>
                        Archivé
                    </option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Enregistrer les modifications
                </button>
                <a href="{{ route('teacher.courses.show', $course->id) }}" class="btn btn-secondary">
    Annuler
</a>
            </div>
        </form>
    </div>
@endsection