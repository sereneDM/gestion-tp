@extends('layouts.app')

@section('title', 'Noter la soumission')
@section('page-title', 'Noter la soumission')

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.submissions.show', $submission) }}
@endsection

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        color: #e2e8f0;
    }
    .btn-secondary {
        background-color: #475569;
        color: white;
    }
    .btn-primary {
        background-color: #4f46e5;
        color: white;
        padding: 0.75rem 1.5rem;
        flex: 1;
        text-align: center;
    }
    .btn:hover {
        opacity: 0.95;
    }
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
    }
    .info-card {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid #334155;
    }
    .info-card h2 {
        color: #c7d2fe;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #334155;
    }
    .info-row {
        display: flex;
        margin-bottom: 1rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #334155;
    }
    .info-label {
        font-weight: bold;
        min-width: 150px;
        color: #94a3b8;
    }
    .info-value {
        color: #e2e8f0;
    }
    .submission-content {
        background-color: #0f172a;
        padding: 1.5rem;
        border-radius: 1rem;
        margin-top: 1rem;
        white-space: pre-wrap;
        min-height: 60px;
        color: #cbd5e1;
        font-style: italic;
        border: 1px solid #334155;
    }
    .form-container {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        border: 1px solid #334155;
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
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
</style>
@endsection

@section('content')
    <div class="info-card">
        <h2>Informations</h2>

        <div class="info-row">
            <div class="info-label">TP:</div>
            <div class="info-value">{{ $submission->tp->title }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Étudiant:</div>
            <div class="info-value">{{ $submission->student->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date de soumission:</div>
            <div class="info-value">{{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Statut actuel:</div>
            <div class="info-value">{{ ucfirst($submission->status) }}</div>
        </div>

        @if($submission->grade)
            <div class="info-row">
                <div class="info-label">Note actuelle:</div>
                <div class="info-value">{{ $submission->grade }}/20</div>
            </div>
        @endif

        @if($submission->attachments)
            <div class="info-row">
                <div class="info-label">Fichier soumis:</div>
                <div class="info-value">
                    <a href="{{ asset('storage/' . $submission->attachments) }}"
                       target="_blank"
                       style="color: #007bff;">
                        📎 Télécharger le fichier de l'étudiant
                    </a>
                </div>
            </div>
        @endif

        <h3 style="margin-top: 1.5rem; margin-bottom: 0.5rem;">Contenu de la soumission:</h3>
        <div class="submission-content">
            {{ $submission->content ?? '(Aucun commentaire)' }}
        </div>
    </div>

    <div class="form-container">
        <h2 style="margin-bottom: 1.5rem;">{{ $submission->grade ? 'Modifier' : 'Attribuer' }} la note</h2>

        <form method="POST" action="{{ route('teacher.submissions.grade', [$submission->tp_id, $submission->id]) }}">
            @csrf

            <div class="form-group">
                <label for="grade">Note (sur 20) *</label>
                <input type="number"
                       id="grade"
                       name="grade"
                       step="0.01"
                       min="0"
                       max="20"
                       value="{{ old('grade', $submission->grade) }}"
                       required>
                @error('grade')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="teacher_comment">Commentaire</label>
                <textarea id="teacher_comment"
                          name="teacher_comment"
                          placeholder="Commentaires pour l'étudiant...">{{ old('teacher_comment', $submission->teacher_comment) }}</textarea>
                @error('teacher_comment')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Enregistrer la note
                </button>
                <a href="{{ route('teacher.tps.show', $submission->tp_id) }}" class="btn btn-secondary">
                    ✗ Annuler
                </a>
            </div>
        </form>
    </div>
@endsection