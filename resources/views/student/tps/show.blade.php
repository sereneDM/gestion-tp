@extends('layouts.student')

@section('title', $tp->title)
@section('page-title', $tp->title)

@section('extra-styles')
<style>
    .back-button {
        margin-bottom: 1.5rem;
    }
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background: #545b62;
    }
    .tp-info-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .tp-info-card h2 {
        color: #007bff;
        margin-top: 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 1.5rem;
    }
    .info-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-label {
        font-weight: bold;
        color: #666;
    }
    .info-value {
        color: #333;
    }
    .submission-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .submission-card h2 {
        margin-top: 0;
        color: #28a745;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .grade-display {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .grade-number {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .comment-box {
        background: #f8f9fa;
        padding: 1.5rem;
        border-left: 4px solid #007bff;
        border-radius: 4px;
        margin-top: 1rem;
    }
    .submit-form {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .submit-form h2 {
        margin-top: 0;
        color: #007bff;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
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
    input[type="file"] {
        width: 100%;
        padding: 0.75rem;
        border: 2px dashed #007bff;
        border-radius: 4px;
        background: #f8f9fa;
    }
    textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        min-height: 100px;
        resize: vertical;
    }
    textarea:focus, input[type="file"]:focus {
        outline: none;
        border-color: #007bff;
    }
    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
    }
    .btn-submit:hover {
        background: #218838;
    }
    .warning-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
    }
    .success-box {
        background: #d4edda;
        border-left: 4px solid #28a745;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
        color: #155724;
    }
    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
    


    <!-- TP Information -->
    <div class="tp-info-card">
        <h2>📝 Détails du TP</h2>

        <div class="info-row">
            <div class="info-label">Cours:</div>
            <div class="info-value">{{ $tp->class->name }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Enseignant:</div>
            <div class="info-value">{{ $tp->teacher->name }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Titre:</div>
            <div class="info-value">{{ $tp->title }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Description:</div>
            <div class="info-value">{{ $tp->description }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Date limite:</div>
            <div class="info-value">
                @if($tp->due_date)
                    {{ $tp->due_date->format('d/m/Y à H:i') }}
                    @if(now()->gt($tp->due_date))
                        <span style="color: #dc3545; font-weight: bold;">(Échéance dépassée)</span>
                    @endif
                @else
                    Pas de date limite
                @endif
            </div>
        </div>

        @if($tp->attachments)
            <div class="info-row">
                <div class="info-label">Énoncé PDF:</div>
                <div class="info-value">
                    <a href="{{ asset('storage/' . $tp->attachments) }}"
                       target="_blank"
                       style="color: #007bff;">
                        📎 Télécharger l'énoncé
                    </a>
                </div>
            </div>
        @endif
    </div>

    @if($submission)
        <!-- Student has submitted -->
        <div class="submission-card">
            <h2>✅ Votre Soumission</h2>

            @if($submission->grade)
                <!-- Graded -->
                <div class="grade-display">
                    <div style="font-size: 1.2rem; margin-bottom: 0.5rem;">Votre note</div>
                    <div class="grade-number">{{ $submission->grade }}/20</div>
                </div>

                @if($submission->teacher_comment)
                    <div class="comment-box">
                        <strong>💬 Commentaire de l'enseignant:</strong>
                        <p style="margin: 0.5rem 0 0 0;">{{ $submission->teacher_comment }}</p>
                    </div>
                @endif
            @else
                <!-- Submitted but not graded yet -->
                <div class="success-box">
                    ✓ Votre travail a été soumis avec succès le {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                </div>
            @endif

            <div class="info-row">
                <div class="info-label">Fichier soumis:</div>
                <div class="info-value">
                    <a href="{{ asset('storage/' . $submission->submission_file) }}"
                       target="_blank"
                       style="color: #007bff;">
                        📥 Télécharger mon fichier
                    </a>
                </div>
            </div>

            @if($submission->comments)
                <div class="info-row">
                    <div class="info-label">Vos commentaires:</div>
                    <div class="info-value">{{ $submission->comments }}</div>
                </div>
            @endif

            <div class="info-row">
                <div class="info-label">Date de soumission:</div>
                <div class="info-value">{{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
            </div>

            @if($submission->status === 'late')
                <div class="warning-box">
                    ⚠️ Soumission en retard
                </div>
            @endif
        </div>
    @else
        <!-- Not submitted yet - show submission form -->
        @if($tp->status === 'closed')
            <div class="warning-box">
                ⚠️ Ce TP n'accepte plus de soumissions.
            </div>
        @else
            <div class="submit-form">
                <h2>📤 Soumettre votre Travail</h2>

                @if($tp->due_date && now()->gt($tp->due_date))
                    <div class="warning-box">
                        ⚠️ <strong>Attention:</strong> La date limite est dépassée. Votre soumission sera marquée comme en retard.
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('student.tps.submit', $tp->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="submission_file">Fichier à soumettre *</label>
                        <input type="file"
                               id="submission_file"
                               name="submission_file"
                               accept=".pdf,.zip,.doc,.docx"
                               required>
                        <small style="color: #666;">Formats acceptés: PDF, ZIP, DOC, DOCX (Max: 10 Mo)</small>
                        @error('submission_file')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="comments">Commentaires (optionnel)</label>
                        <textarea id="comments"
                                  name="comments"
                                  placeholder="Ajoutez des commentaires sur votre travail...">{{ old('comments') }}</textarea>
                        @error('comments')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        ✓ Soumettre mon travail
                    </button>
                </form>
            </div>
        @endif
    @endif
@endsection