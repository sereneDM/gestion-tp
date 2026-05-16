@extends('layouts.admin')

@section('title', 'Modifier la Classe')

@section('breadcrumb')
    <a href="{{ route('admin.classes.index') }}" class="tb-bc-page" style="text-decoration:none;">Classes</a>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">{{ $class->name }}</span>
@endsection

@section('extra-styles')
<style>
    .students-selection {
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        background: var(--surface-2);
        max-height: 260px;
        overflow-y: auto;
        padding: 0.5rem;
    }

    .student-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: background 0.2s;
    }

    .student-item:hover {
        background: var(--surface-3);
    }

    .student-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--accent);
    }

    .student-info {
        display: flex;
        flex-direction: column;
    }

    .student-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ink);
    }

    .student-email {
        font-size: 0.75rem;
        color: var(--ink-4);
    }
</style>
@endsection

@section('content')
<div style="max-width: 640px;">
    <h1 class="page-title">Modifier la Classe</h1>
    <p class="page-subtitle">Mise à jour des informations et des étudiants assignés.</p>

    <div class="card" style="padding: 28px;">
        <form method="POST" action="{{ route('admin.classes.update', $class->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="label" for="name">Nom de la classe</label>
                <input type="text" id="name" name="name" class="input" value="{{ old('name', $class->name) }}" required autofocus>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="description">Description (Optionnelle)</label>
                <textarea id="description" name="description" class="input">{{ old('description', $class->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="teacher_id">Enseignant responsable</label>
                <select id="teacher_id" name="teacher_id" class="input" style="appearance:none; background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%239aa3af\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'%3E%3C/path%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;">
                    <option value="">-- Sélectionner un enseignant --</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label">Gestion des étudiants</label>
                <div class="students-selection">
                    @php
                        $selectedStudents = old('students', $class->students->pluck('id')->toArray());
                    @endphp
                    @forelse($students as $student)
                        <label class="student-item" for="student_{{ $student->id }}">
                            <input type="checkbox" name="students[]" value="{{ $student->id }}" id="student_{{ $student->id }}" {{ in_array($student->id, $selectedStudents) ? 'checked' : '' }}>
                            <div class="student-info">
                                <span class="student-name">{{ $student->name }}</span>
                                <span class="student-email">{{ $student->email }}</span>
                            </div>
                        </label>
                    @empty
                        <div style="padding:2rem; text-align:center; color:var(--ink-4); font-size:0.875rem;">
                            Aucun étudiant disponible
                        </div>
                    @endforelse
                </div>
                @error('students') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection