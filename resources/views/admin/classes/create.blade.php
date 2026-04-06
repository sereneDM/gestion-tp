@extends('layouts.admin')

@section('title', 'Créer une Classe')
@section('page-title', 'Créer une Classe')

@section('extra-styles')
<style>
    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 800px;
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
    input, select, textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        font-family: Arial, sans-serif;
    }
    textarea {
        min-height: 100px;
        resize: vertical;
    }
    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #007bff;
    }
    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .students-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 1rem;
    }
    .student-checkbox {
        display: flex;
        align-items: center;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        border-radius: 4px;
    }
    .student-checkbox:hover {
        background-color: #f8f9fa;
    }
    .student-checkbox input {
        width: auto;
        margin-right: 0.75rem;
    }
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        display: inline-block;
        flex: 1;
        text-align: center;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #545b62;
    }
</style>
@endsection

@section('content')
    <div class="form-container">
        <form method="POST" action="{{ route('admin.classes.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nom de la classe *</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Ex: Classe 3A, Groupe TP1"
                       required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description"
                          placeholder="Description optionnelle de la classe">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="teacher_id">Enseignant assigné</label>
                <select id="teacher_id" name="teacher_id">
                    <option value="">-- Aucun enseignant --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Étudiants (sélectionnez plusieurs)</label>
                <div class="students-list">
                    @forelse($students as $student)
                        <div class="student-checkbox">
                            <input type="checkbox"
                                   name="students[]"
                                   value="{{ $student->id }}"
                                   id="student_{{ $student->id }}"
                                   {{ in_array($student->id, old('students', [])) ? 'checked' : '' }}>
                            <label for="student_{{ $student->id }}" style="margin: 0; font-weight: normal;">
                                {{ $student->name }} ({{ $student->email }})
                            </label>
                        </div>
                    @empty
                        <p style="color: #999;">Aucun étudiant disponible</p>
                    @endforelse
                </div>
                @error('students')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Créer la classe
                </button>
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                    ✗ Annuler
                </a>
            </div>
        </form>
    </div>
@endsection