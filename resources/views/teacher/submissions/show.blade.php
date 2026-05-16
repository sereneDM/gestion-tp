@extends('layouts.app')

@section('title', 'Noter la soumission')
@section('page-title', 'Noter la soumission')

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.submissions.show', $submission) }}
@endsection

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
:root {
    --ink:        #0d1117;
    --ink-2:      #3d4550;
    --ink-3:      #6b7585;
    --ink-4:      #9aa3af;
    --line:       #e8ebef;
    --line-2:     #d1d6dd;
    --surface:    #ffffff;
    --surface-2:  #f5f6f8;
    --surface-3:  #eef0f3;
    --accent:     #3d5afe;
    --accent-2:   #5271ff;
    --accent-bg:  #eef1ff;
    --danger:     #e53935;
    --danger-bg:  #fff0f0;
    --warning:    #f59e0b;
    --success:    #10b981;
    --success-bg: #ecfdf5;
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 800px; margin: 0 auto; padding: 0.5rem 0 3rem; display: flex; flex-direction: column; gap: 1.5rem; }

.card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.75rem;
    border-bottom: 1px solid var(--line);
}
.card-header-title {
    display: flex; align-items: center; gap: 0.6rem;
    font-size: 1rem; font-weight: 700; color: var(--ink);
}
.card-header-title i { font-size: 20px; color: var(--accent); }

.card-body { padding: 1.5rem 1.75rem; }

/* ── Info rows ── */
.info-row {
    display: grid;
    grid-template-columns: 180px 1fr;
    gap: 1rem;
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--line);
    align-items: start;
}
.info-row:last-child { border-bottom: none; padding-bottom: 0; }
.info-row:first-child { padding-top: 0; }

.info-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--ink-4);
    padding-top: 0.2rem;
}

.info-value {
    font-size: 0.95rem; color: var(--ink-2);
    line-height: 1.6; word-break: break-word;
}
.info-value.desc-value {
    white-space: pre-wrap;
    background: var(--surface-2);
    padding: 1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    font-style: italic;
}

.attachment-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.4rem 1rem;
    background: var(--surface-2); border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    color: var(--ink-2); font-size: 0.85rem; font-weight: 600;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
}
.attachment-btn i { font-size: 16px; color: var(--accent); }
.attachment-btn:hover { background: var(--surface-3); border-color: var(--line-2); }

/* ── Form ── */
.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; margin-bottom: 0.5rem; color: var(--ink); font-weight: 600; font-size: 0.9rem; }
.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 0.95rem;
    font-family: var(--font-body);
    background: var(--surface);
    color: var(--ink);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}
textarea.form-input { min-height: 120px; resize: vertical; line-height: 1.6; }

.error { font-size: 0.8rem; color: var(--danger); margin-top: 0.4rem; display: flex; align-items: center; gap: 4px; }

/* ── Footer ── */
.form-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.1rem 1.75rem;
    border-top: 1px solid var(--line);
    background: var(--surface-2);
}
.btn-cancel {
    padding: 0.6rem 1.1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--surface); color: var(--ink-2);
    font-size: 0.875rem; font-weight: 600;
    font-family: var(--font-body);
    text-decoration: none; cursor: pointer;
    transition: background 0.15s;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-cancel:hover { background: var(--surface-3); }

.btn-submit {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.65rem 1.4rem;
    border-radius: var(--radius-md); border: none;
    background: var(--accent); color: white;
    font-size: 0.875rem; font-weight: 700;
    font-family: var(--font-body); cursor: pointer;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
    transition: background 0.2s, transform 0.15s;
}
.btn-submit:hover { background: var(--accent-2); transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="ti ti-info-circle"></i> Informations de la soumission
            </div>
        </div>
        <div class="card-body">
            <div class="info-row">
                <div class="info-label">TP</div>
                <div class="info-value" style="font-weight:600; color:var(--ink);">{{ $submission->tp->title }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Étudiant</div>
                <div class="info-value">
                    <span style="display:inline-flex;align-items:center;gap:0.4rem;background:var(--surface-2);padding:0.2rem 0.6rem;border-radius:100px;font-size:0.85rem;border:1px solid var(--line);"><i class="ti ti-user" style="color:var(--ink-4);"></i> {{ $submission->student->name }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Date de soumission</div>
                <div class="info-value">
                    <span style="display:inline-flex;align-items:center;gap:0.4rem;">
                        <i class="ti ti-calendar-event" style="font-size:16px;color:var(--ink-4);"></i>
                        {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Statut actuel</div>
                <div class="info-value">
                    @if($submission->grade !== null)
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:0.2rem 0.65rem;border-radius:100px;font-size:0.75rem;font-weight:700;background:var(--success-bg);color:var(--success);">
                            <i class="ti ti-check" style="font-size:12px;"></i> Noté
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:0.2rem 0.65rem;border-radius:100px;font-size:0.75rem;font-weight:700;background:var(--warning-bg);color:var(--warning);">
                            <i class="ti ti-clock" style="font-size:12px;"></i> En attente
                        </span>
                    @endif
                </div>
            </div>

            @if($submission->grade)
                <div class="info-row">
                    <div class="info-label">Note actuelle</div>
                    <div class="info-value" style="font-weight:700; color:var(--accent); font-size:1.1rem;">
                        {{ $submission->grade }}/20
                    </div>
                </div>
            @endif

            @if($submission->attachments)
                <div class="info-row">
                    <div class="info-label">Fichier soumis</div>
                    <div class="info-value" style="display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-start;">
                        @foreach((array)$submission->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="attachment-btn">
                                <i class="ti ti-download"></i> Télécharger {{ basename($attachment) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="info-row" style="border-bottom:none; display:block;">
                <div class="info-label" style="margin-bottom: 0.75rem;">Contenu de la soumission :</div>
                <div class="info-value desc-value">
                    {{ $submission->content ?? '(Aucun commentaire)' }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="ti ti-award" style="color:var(--warning);"></i> {{ $submission->grade ? 'Modifier' : 'Attribuer' }} la note
            </div>
        </div>
        <form method="POST" action="{{ route('teacher.submissions.grade', [$submission->tp_id, $submission->id]) }}">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="grade">Note (sur 20) *</label>
                    <input type="number"
                           id="grade"
                           name="grade"
                           class="form-input"
                           step="0.01"
                           min="0"
                           max="20"
                           value="{{ old('grade', $submission->grade) }}"
                           required>
                    @error('grade')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label for="teacher_comment">Commentaire de l'enseignant</label>
                    <textarea id="teacher_comment"
                              name="teacher_comment"
                              class="form-input"
                              placeholder="Commentaires pour l'étudiant...">{{ old('teacher_comment', $submission->teacher_comment) }}</textarea>
                    @error('teacher_comment')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-card-footer">
                <a href="{{ route('teacher.tps.show', $submission->tp_id) }}" class="btn-cancel">
                    <i class="ti ti-x"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="ti ti-device-floppy"></i> Enregistrer la note
                </button>
            </div>
        </form>
    </div>

</div>
@endsection