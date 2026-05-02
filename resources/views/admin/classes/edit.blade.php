@extends('layouts.app')

@section('title', 'Modifier une Classe')
@section('page-title', 'Modifier une Classe')

@section('extra-styles')
<style>
    .form-container {
        @apply bg-white dark:bg-slate-800 px-8 py-8 rounded-2xl shadow-md dark:shadow-lg max-w-4xl border border-slate-200 dark:border-slate-700;
    }
    .form-group {
        @apply mb-6;
    }
    label {
        @apply block mb-2 text-slate-900 dark:text-slate-100 font-bold;
    }
    input, select, textarea {
        @apply w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-base font-sans bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400;
    }
    textarea {
        @apply min-h-[100px] resize-vertical;
    }
    input:focus, select:focus, textarea:focus {
        @apply outline-none border-violet-500 dark:border-violet-400 ring-2 ring-violet-200 dark:ring-violet-900/30;
    }
    .error {
        @apply text-red-600 dark:text-red-400 text-sm mt-1;
    }
    .students-list {
        @apply max-h-[300px] overflow-y-auto border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-3 bg-white dark:bg-slate-700;
    }
    .student-checkbox {
        @apply flex items-center py-2 px-2 mb-1 rounded-lg transition-colors hover:bg-slate-100 dark:hover:bg-slate-600;
    }
    .student-checkbox input {
        @apply w-auto mr-3;
    }
    .button-group {
        @apply flex gap-4 mt-8;
    }
    .btn {
        @apply px-6 py-3 border-none rounded-lg cursor-pointer no-underline text-base inline-flex items-center justify-center flex-1 font-medium transition-colors duration-200;
    }
    .btn-primary {
        @apply bg-amber-500 dark:bg-amber-600 text-slate-900 dark:text-white hover:bg-amber-600 dark:hover:bg-amber-700;
    }
    .btn-secondary {
        @apply bg-slate-400 dark:bg-slate-600 text-white hover:bg-slate-500 dark:hover:bg-slate-700;
    }
</style>
@endsection
        background-color: #334155;
    }
</style>
@endsection

@section('content')
    <div class="form-container">
        <form method="POST" action="{{ route('admin.classes.update', $class->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nom de la classe *</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $class->name) }}"
                       required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description">{{ old('description', $class->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="teacher_id">Enseignant assigné</label>
                <select id="teacher_id" name="teacher_id">
                    <option value="">-- Aucun enseignant --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                                {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
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
                    @php
                        $selectedStudents = old('students', $class->students->pluck('id')->toArray());
                    @endphp
                    @forelse($students as $student)
                        <div class="student-checkbox">
                            <input type="checkbox"
                                   name="students[]"
                                   value="{{ $student->id }}"
                                   id="student_{{ $student->id }}"
                                   {{ in_array($student->id, $selectedStudents) ? 'checked' : '' }}>
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
                    ✓ Enregistrer les modifications
                </button>
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                    ✗ Annuler
                </a>
            </div>
        </form>
    </div>
@endsection