@extends('layouts.app')

@section('title', Str::limit($tp->title, 50))
@section('page-title', Str::limit($tp->title, 50))

@section('extra-styles')
<style>
    .btn { padding: 0.6rem 1.2rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
    .btn-secondary { background: #475569; color: white; }
    .btn-secondary:hover { background: #334155; }
    .tp-info-card { background: #1e293b; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; }
    .tp-info-card h2 { color: #3b82f6; margin-top: 0; padding-bottom: 0.5rem; border-bottom: 2px solid #475569; margin-bottom: 1.5rem; }
    .info-row { display: grid; grid-template-columns: 200px 1fr; padding: 1rem 0; border-bottom: 1px solid #475569; }
    .info-label { font-weight: bold; color: #94a3b8; }
    .info-value { color: #e2e8f0; word-break: break-word; overflow-wrap: break-word; }
    .submission-card { background: #1e293b; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; }
    .submission-card h2 { margin-top: 0; color: #10b981; border-bottom: 2px solid #475569; padding-bottom: 0.5rem; margin-bottom: 1.5rem; }
    .grade-display { background: #4f46e5; color: white; padding: 2rem; border-radius: 8px; text-align: center; margin-bottom: 1.5rem; }
    .grade-number { font-size: 3rem; font-weight: bold; margin-bottom: 0.5rem; }
    .comment-box { background: #334155; padding: 1.5rem; border-left: 4px solid #3b82f6; border-radius: 4px; margin-top: 1rem; }
    .submit-form { background: #1e293b; padding: 2rem; border-radius: 8px; }
    .submit-form h2 { margin-top: 0; color: #3b82f6; border-bottom: 2px solid #475569; padding-bottom: 0.5rem; margin-bottom: 1.5rem; }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: #e2e8f0; font-weight: bold; }
    .btn-submit { width: 100%; padding: 1rem; background: #10b981; color: white; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; margin-top: 1rem; }
    .btn-submit:hover { background: #059669; }
    .warning-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px; }
    .success-box { background: #d4edda; border-left: 4px solid #28a745; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px; color: #155724; }
    .error { color: #dc3545; font-size: 0.875rem; margin-top: 0.5rem; }

    .enonce-box { border: 1px solid #475569; border-radius: 4px; overflow: hidden; }
    .enonce-box textarea {
        border: none;
        border-bottom: 1px solid #475569;
        border-radius: 0;
        margin: 0;
        width: 100%;
        padding: 0.75rem;
        min-height: 120px;
        resize: vertical;
        font-size: 1rem;
        font-family: inherit;
        background: #1e293b;
        color: #e2e8f0;
    }
    .enonce-box textarea:focus { outline: none; box-shadow: none; }
    .pdf-section { padding: 1rem; background: #334155; }
    .pdf-section-label { font-size: 0.85rem; font-weight: bold; color: #94a3b8; margin-bottom: 0.75rem; display: block; }
    .file-upload input[type="file"] { display: none; }
</style>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('student.tps.show', $tp) }}
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
                    Pas d'échéance définie
                @endif
            </div>
        </div>
        @if($tp->attachments)
            <div class="info-row">
                <div class="info-label">Énoncé PDF:</div>
                <div class="info-value" style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach((array)$tp->attachments as $attachment)
                        <a href="{{ asset('storage/' . $attachment) }}" target="_blank" style="color: #3b82f6;">
                            📎 Télécharger {{ basename($attachment) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @if($tp->due_date && now()->lt($tp->due_date) && !$submission)
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
<style>
    #clock-widget {
    position: fixed; top: 4rem; right: 1rem; z-index: 999;
    display: inline-flex; flex-direction: column; align-items: flex-end;
    padding: 6px 10px 5px;
    background: rgba(15, 20, 35, 0.72);
    backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(59,130,246,0.25);
    border-radius: 8px; gap: 2px; user-select: none;
}
    #clock-widget .cw-label {
        font-size: 10px; font-weight: 500; letter-spacing: 0.12em;
        text-transform: uppercase; color: rgba(148,163,184,0.85);
    }
    #clock-widget .cw-digits {
        font-family: 'Share Tech Mono', monospace;
        font-size: 20px; color: #38bdf8; line-height: 1;
        letter-spacing: 0.04em; display: flex; align-items: center;
    }
    #clock-widget .cw-sep {
        color: rgba(56,189,248,0.45); margin: 0 2px;
        animation: cw-blink 1s step-end infinite;
    }
    @keyframes cw-blink { 50% { opacity: 0.15; } }
    #clock-widget .cw-days {
        font-family: 'Share Tech Mono', monospace;
        font-size: 10px; color: rgba(56,189,248,0.6);
        letter-spacing: 0.08em; text-align: right;
    }
    #clock-widget .cw-due {
        font-size: 9px; color: rgba(148,163,184,0.55);
        letter-spacing: 0.04em; border-top: 1px solid rgba(59,130,246,0.12);
        padding-top: 4px; width: 100%; text-align: right;
    }
    #clock-widget.urgent { border-color: rgba(248,113,113,0.3) !important; }
    #clock-widget.urgent .cw-digits { color: #f87171; }
    #clock-widget.urgent .cw-sep { color: rgba(248,113,113,0.4); }
</style>

<div id="clock-widget">
    <div class="cw-label">⏰ Temps restant</div>
    <div class="cw-digits">
        <span id="cw-h">--</span>
        <span class="cw-sep">:</span>
        <span id="cw-m">--</span>
        <span class="cw-sep">:</span>
        <span id="cw-s">--</span>
    </div>
    <div class="cw-days" id="cw-d"></div>
    <div class="cw-due">{{ $tp->due_date->format('d/m/Y à H:i') }}</div>
</div>

<script>
    const _deadline = new Date("{{ $tp->due_date->toIso8601String() }}");
    const _cw = document.getElementById('clock-widget');
    function _cwPad(n) { return String(n).padStart(2,'0'); }
    function _cwTick() {
        const diff = _deadline - new Date();
        if (diff <= 0) {
            document.getElementById('cw-h').textContent = '00';
            document.getElementById('cw-m').textContent = '00';
            document.getElementById('cw-s').textContent = '00';
            document.getElementById('cw-d').textContent = 'Échéance dépassée';
            _cw.classList.add('urgent'); return;
        }
        const days  = Math.floor(diff / 86400000);
        const hours = Math.floor((diff % 86400000) / 3600000);
        const mins  = Math.floor((diff % 3600000) / 60000);
        const secs  = Math.floor((diff % 60000) / 1000);
        document.getElementById('cw-h').textContent = _cwPad(hours);
        document.getElementById('cw-m').textContent = _cwPad(mins);
        document.getElementById('cw-s').textContent = _cwPad(secs);
        document.getElementById('cw-d').textContent = days > 0 ? days + (days === 1 ? ' jour' : ' jours') : '';
        if (diff < 3600000) _cw.classList.add('urgent');
    }
    _cwTick(); setInterval(_cwTick, 1000);
</script>
@endif

    {{-- Submission form for students who haven't submitted yet --}}
    @if(!$submission && (!$tp->due_date || now()->lt($tp->due_date)))
        <div class="submit-form">
            <h2>📤 Soumettre votre travail</h2>



            <form method="POST" action="{{ route('student.tps.submit', $tp->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Commentaires / Réponse</label>
                    <div class="enonce-box">
                        <textarea id="comments"
                                  name="comments"
                                  placeholder="Rédigez votre réponse..."
                                  >{{ old('comments') }}</textarea>
                        <div class="pdf-section">
                            <span class="pdf-section-label">📎 Joindre un fichier (optionnel)</span>
                            <x-file-upload id="submission_file" name="submission_file"
    accept=".pdf,.zip,.doc,.docx"
    hint="PDF, ZIP, DOC, DOCX · max 50 Mo"
    :required="true" />
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">📤 Soumettre mon travail</button>
            </form>
        </div>
    @endif

    @if($submission)
        <div class="submission-card">
            <h2>✅ Votre Soumission</h2>

            @if($submission->grade)
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
                <div class="success-box">
                    ✓ Votre travail a été soumis avec succès le {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                </div>
            @endif

            @if($submission->attachments)
                <div class="info-row">
                    <div class="info-label">Fichier(s) soumis:</div>
                    <div class="info-value" style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach((array)$submission->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment) }}" target="_blank" style="color: #3b82f6;">
                                📥 Télécharger {{ basename($attachment) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($submission->content)
                <div class="info-row">
                    <div class="info-label">Vos commentaires:</div>
                    <div class="info-value">{{ $submission->content }}</div>
                </div>
            @endif

            <div class="info-row">
                <div class="info-label">Date de soumission:</div>
                <div class="info-value">{{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
            </div>


            @if(!$submission->grade && (!$tp->due_date || now()->lt($tp->due_date)))
                <button onclick="document.getElementById('edit-form').style.display='block'; this.style.display='none';"
                        style="margin-top:1rem; padding:0.75rem 1.5rem; background:#f59e0b; color:#1f2937; border:none; border-radius:4px; cursor:pointer; font-weight:bold;">
                    ✏️ Modifier ma soumission
                </button>
            @endif
        </div>

        @if(!$submission->grade && (!$tp->due_date || now()->lt($tp->due_date)))
            <div id="edit-form" style="display:none;">
                <div class="submit-form">
                    <h2>✏️ Modifier votre Soumission</h2>

                    <form method="POST" action="{{ route('student.tps.update-submission', $tp->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Commentaires / Réponse</label>
                            <div class="enonce-box">
                                <textarea id="comments"
                                          name="comments"
                                          placeholder="Rédigez votre réponse...">{{ old('comments', $submission->content) }}</textarea>
                                <div class="pdf-section">
                                    <span class="pdf-section-label">📎 Remplacer le fichier (optionnel)</span>
                                    <x-file-upload id="submission_file" name="submission_file"
    accept=".pdf,.zip,.doc,.docx"
    hint="PDF, ZIP, DOC, DOCX · max 50 Mo"
    :required="true" />
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">✓ Enregistrer les modifications</button>
                    </form>
                </div>
            </div>
        @endif
    @endif

@endsection