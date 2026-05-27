
@extends('layouts.app')

@section('title', Str::limit($tp->title, 50))
@section('page-title', Str::limit($tp->title, 50))

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
    --warning-bg: #fffbeb;
    --success:    #10b981;
    --success-bg: #ecfdf5;
    --info:       #0ea5e9;
    --info-bg:    #f0f9ff;
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 900px; margin: 0 auto; padding: 0.5rem 0 3rem; display: flex; flex-direction: column; gap: 1.5rem; }

/* ── Card ── */
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
}

/* ── Attachment Btn ── */
.attachment-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.4rem 1rem;
    background: var(--surface-2); border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    color: var(--ink-2); font-size: 0.85rem; font-weight: 600;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
    cursor: pointer;
}
.attachment-btn i { font-size: 16px; color: var(--accent); }
.attachment-btn:hover { background: var(--surface-3); border-color: var(--line-2); }

/* ── Submission Grade ── */
.grade-display {
    background: var(--accent-bg); border: 1px solid rgba(61,90,254,0.15);
    padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; margin-bottom: 1.5rem;
}
.grade-title { font-size: 0.85rem; font-weight: 600; color: var(--accent-2); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
.grade-number { font-size: 3rem; font-weight: 700; color: var(--accent); font-family: var(--font-serif); line-height: 1; }

.comment-box {
    background: var(--surface-2); border-left: 4px solid var(--accent);
    padding: 1.25rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-top: 1.5rem;
}
.comment-box strong { display: flex; align-items: center; gap: 0.4rem; color: var(--ink); margin-bottom: 0.5rem; font-size: 0.9rem; }
.comment-box p { margin: 0; color: var(--ink-2); font-size: 0.9rem; line-height: 1.6; }

.success-box {
    background: var(--success-bg); border-left: 4px solid var(--success);
    padding: 1rem 1.25rem; margin-bottom: 1.5rem; border-radius: 0 var(--radius-md) var(--radius-md) 0;
    color: var(--success); font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;
}

/* ── Form ── */
.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; margin-bottom: 0.5rem; color: var(--ink); font-weight: 600; font-size: 0.9rem; }
.enonce-box { border: 1px solid var(--line); border-radius: var(--radius-md); overflow: hidden; background: var(--surface); transition: border-color 0.2s; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02); }
.enonce-box:focus-within { border-color: var(--accent); }
.enonce-box textarea {
    border: none; border-bottom: 1px solid var(--line); border-radius: 0; margin: 0; width: 100%;
    padding: 1rem; min-height: 140px; resize: vertical; font-size: 0.95rem; font-family: var(--font-body);
    background: transparent; color: var(--ink);
}
.enonce-box textarea:focus { outline: none; box-shadow: none; }
.pdf-section { padding: 1rem; background: var(--surface-2); }
.pdf-section-label { font-size: 0.85rem; font-weight: 600; color: var(--ink-3); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem; }

.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
    width: 100%; padding: 0.8rem; background: var(--success); color: white;
    border: none; border-radius: var(--radius-md); font-size: 0.95rem; font-weight: 600; font-family: var(--font-body);
    cursor: pointer; transition: background 0.15s, transform 0.15s; box-shadow: 0 2px 8px rgba(16,185,129,0.3);
}
.btn:hover { background: #059669; transform: translateY(-1px); }
.btn-warning { background: var(--warning); color: white; box-shadow: 0 2px 8px rgba(245,158,11,0.3); }
.btn-warning:hover { background: #d97706; }

.current-file {
    display: flex; align-items: center; gap: 0.6rem;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    padding: 0.6rem 0.9rem;
    font-size: 0.82rem; color: var(--ink-2);
    margin-bottom: 0.65rem;
}
.current-file i { font-size: 15px; color: var(--accent); flex-shrink: 0; }
.current-file a { color: var(--accent); text-decoration: none; }
.current-file a:hover { text-decoration: underline; }

.file-upload {
    border: 2px dashed var(--line-2);
    padding: 1rem; text-align: center;
    border-radius: var(--radius-md);
    background: var(--surface); cursor: pointer;
    transition: all 0.2s;
    font-size: 0.85rem; color: var(--ink-3);
}
.file-upload:hover { background: var(--accent-bg); border-color: var(--accent); color: var(--accent); }
.file-upload input[type="file"] { display: none; }
.file-upload i { font-size: 20px; display: block; margin-bottom: 0.4rem; }
.file-upload-hint { font-size: 0.72rem; color: var(--ink-4); margin-top: 0.25rem; }

.selected-file {
    display: flex; align-items: center; gap: 0.5rem;
    margin-top: 0.5rem; padding: 0.4rem 0.75rem;
    background: var(--success-bg);
    border: 1px solid rgba(16,185,129,0.2);
    border-radius: var(--radius-sm);
    font-size: 0.8rem; color: var(--success);
}
.selected-file i { font-size: 13px; }
</style>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('student.tps.show', $tp) }}
@endsection

@section('content')
<div class="page-wrapper">

    <!-- TP Information -->
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="ti ti-file-description"></i> Détails du TP
            </div>
        </div>
        <div class="card-body">
            <div class="info-row">
                <div class="info-label">Cours</div>
                <div class="info-value">{{ $tp->class->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Enseignant</div>
                <div class="info-value">
                    <span style="display:inline-flex;align-items:center;gap:0.4rem;background:var(--surface-2);padding:0.2rem 0.6rem;border-radius:100px;font-size:0.85rem;border:1px solid var(--line);">
                        <i class="ti ti-user" style="color:var(--ink-4);"></i> {{ $tp->teacher->name }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Titre</div>
                <div class="info-value" style="font-weight:700;color:var(--ink);font-size:1.05rem;">{{ $tp->title }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Description</div>
                <div class="info-value desc-value">{{ $tp->description }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date limite</div>
                <div class="info-value">
                    @if($tp->due_date)
                        <span style="display:inline-flex;align-items:center;gap:0.4rem;">
                            <i class="ti ti-calendar-due" style="font-size:16px;color:var(--ink-4);"></i>
                            {{ $tp->due_date->format('d/m/Y à H:i') }}
                        </span>
                        @if(now()->gt($tp->due_date))
                            <span style="color:var(--danger);font-weight:600;margin-left:0.5rem;background:var(--danger-bg);padding:0.1rem 0.5rem;border-radius:100px;font-size:0.75rem;">Échéance dépassée</span>
                        @endif
                    @else
                        <span style="color:var(--ink-4);font-style:italic;">Pas d'échéance définie</span>
                    @endif
                </div>
            </div>

            {{-- Énoncé PDF — top info card (always visible) --}}
            @if($tp->attachments)
                <div class="info-row">
                    <div class="info-label">Énoncé PDF</div>
                    <div class="info-value" style="display:flex; flex-direction:column; gap:0.5rem; align-items:flex-start;">
                        @foreach((array)$tp->attachments as $attachment)
                            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                                <button type="button" class="attachment-btn" onclick="toggleTpPdf(this)"
                                        data-target="tp-pdf-top-{{ $loop->index }}">
                                    <i class="ti ti-eye"></i>
                                    <span>Afficher</span>
                                </button>
                                <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="attachment-btn">
                                    <i class="ti ti-download"></i> Télécharger {{ basename($attachment) }}
                                </a>
                            </div>
                            <div id="tp-pdf-top-{{ $loop->index }}"
                                 style="display:none; width:100%; margin-top:0.5rem; border:1px solid var(--line); border-radius:var(--radius-md); overflow:hidden;">
                                <iframe src="{{ asset('storage/' . $attachment) }}"
                                        style="width:100%; height:600px; border:none; display:block;"
                                        title="Aperçu PDF"></iframe>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    @if($tp->due_date && now()->lt($tp->due_date) && !$submission)
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
<style>
    #clock-widget {
        position: fixed; top: 4.5rem; right: 1.5rem; z-index: 999;
        display: inline-flex; flex-direction: column; align-items: flex-end;
        padding: 8px 12px 6px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        border: 1px solid var(--line-2);
        border-radius: var(--radius-md); gap: 2px; user-select: none;
        box-shadow: var(--shadow-md);
    }
    #clock-widget .cw-label {
        font-size: 10px; font-weight: 700; letter-spacing: 0.08em;
        text-transform: uppercase; color: var(--ink-3); font-family: var(--font-body);
    }
    #clock-widget .cw-digits {
        font-family: 'Share Tech Mono', monospace;
        font-size: 22px; color: var(--accent); line-height: 1;
        letter-spacing: 0.02em; display: flex; align-items: center;
    }
    #clock-widget .cw-sep {
        color: rgba(61,90,254,0.45); margin: 0 2px;
        animation: cw-blink 1s step-end infinite;
    }
    @keyframes cw-blink { 50% { opacity: 0.25; } }
    #clock-widget .cw-days {
        font-family: 'Share Tech Mono', monospace;
        font-size: 11px; color: rgba(61,90,254,0.8);
        letter-spacing: 0.05em; text-align: right; margin-top: -2px;
    }
    #clock-widget .cw-due {
        font-size: 9px; color: var(--ink-4); font-family: var(--font-body); font-weight: 500;
        letter-spacing: 0.02em; border-top: 1px solid var(--line);
        padding-top: 4px; width: 100%; text-align: right; margin-top: 2px;
    }
    #clock-widget.urgent { border-color: rgba(239,68,68,0.4) !important; background: rgba(254,242,242,0.9); }
    #clock-widget.urgent .cw-digits { color: var(--danger); }
    #clock-widget.urgent .cw-sep { color: rgba(239,68,68,0.4); }
    #clock-widget.urgent .cw-days { color: rgba(239,68,68,0.8); }
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
    // ...existing code...
</script>
@endif

    {{-- Submission form for students who haven't submitted yet --}}
    @if(!$submission && (!$tp->due_date || now()->lt($tp->due_date)))
        <div class="card">
            <div class="card-header">
                <div class="card-header-title">
                    <i class="ti ti-upload" style="color:var(--info);"></i> Soumettre votre travail
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.tps.submit', $tp->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Commentaires / Réponse</label>
                        <div class="enonce-box">
                            <textarea id="comments"
                                      name="comments"
                                      placeholder="Rédigez votre réponse...">{{ old('comments') }}</textarea>
                            <div class="pdf-section">
                                <span class="pdf-section-label"><i class="ti ti-paperclip"></i> Joindre un fichier (optionnel)</span>
                                <x-file-upload id="submission_file" name="submission_file"
                                               accept=".pdf,.zip,.doc,.docx"
                                               hint="PDF, ZIP, DOC, DOCX · max 50 Mo"
                                               :required="true" />
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn"><i class="ti ti-send"></i> Soumettre mon travail</button>
                </form>
            </div>
        </div>
    @endif

    @if($submission)
        <div class="card">
            <div class="card-header">
                <div class="card-header-title">
                    <i class="ti ti-circle-check-filled" style="color:var(--success);"></i> Votre Soumission
                </div>
            </div>
            <div class="card-body">
                @if($submission->grade)
                    <div class="grade-display">
                        <div class="grade-title">Votre note</div>
                        <div class="grade-number">{{ $submission->grade }}/20</div>
                    </div>
                    @if($submission->teacher_comment)
                        <div class="comment-box">
                            <strong><i class="ti ti-messages"></i> Commentaire de l'enseignant</strong>
                            <p>{{ $submission->teacher_comment }}</p>
                        </div>
                    @endif
                @else
                    <div class="success-box">
                        <i class="ti ti-check"></i> Votre travail a été soumis avec succès le {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                    </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Date de soumission</div>
                    <div class="info-value">
                        <span style="display:inline-flex;align-items:center;gap:0.4rem;background:var(--surface-2);padding:0.2rem 0.6rem;border-radius:100px;font-size:0.85rem;border:1px solid var(--line);">
                            <i class="ti ti-calendar-event" style="color:var(--ink-4);"></i> {{ $submission->submitted_at->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                </div>

                @if($submission->content)
                    <div class="info-row">
                        <div class="info-label">Vos commentaires</div>
                        <div class="info-value desc-value">{{ $submission->content }}</div>
                    </div>
                @endif

                {{-- Énoncé PDF — inside submission card (unique tp-pdf-sub- prefix) --}}
                @if($tp->attachments)
                    <div class="info-row">
                        <div class="info-label">Énoncé PDF</div>
                        <div class="info-value" style="display:flex; flex-direction:column; gap:0.5rem; align-items:flex-start;">
                            @foreach((array)$tp->attachments as $attachment)
                                <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                                    <button type="button" class="attachment-btn" onclick="toggleTpPdf(this)"
                                            data-target="tp-pdf-sub-{{ $loop->index }}">
                                        <i class="ti ti-eye"></i>
                                        <span>Afficher</span>
                                    </button>
                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="attachment-btn">
                                        <i class="ti ti-download"></i> Télécharger {{ basename($attachment) }}
                                    </a>
                                </div>
                                <div id="tp-pdf-sub-{{ $loop->index }}"
                                     style="display:none; width:100%; margin-top:0.5rem; border:1px solid var(--line); border-radius:var(--radius-md); overflow:hidden;">
                                    <iframe src="{{ asset('storage/' . $attachment) }}"
                                            style="width:100%; height:600px; border:none; display:block;"
                                            title="Aperçu PDF"></iframe>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!$submission->grade && (!$tp->due_date || now()->lt($tp->due_date)))
                    <div style="margin-top:2rem; border-top:1px solid var(--line); padding-top:1.5rem;">
                        <button onclick="document.getElementById('edit-form').style.display='block'; this.style.display='none';"
                                class="btn btn-warning">
                            <i class="ti ti-pencil"></i> Modifier ma soumission
                        </button>
                    </div>
                @endif
            </div>
        </div>

        @if(!$submission->grade && (!$tp->due_date || now()->lt($tp->due_date)))
            <div id="edit-form" style="display:none;">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-title">
                            <i class="ti ti-edit" style="color:var(--warning);"></i> Modifier votre Soumission
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('student.tps.update-submission', $tp->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Commentaires / Réponse</label>
                                <div class="enonce-box">
                                    <textarea id="edit-comments"
                                              name="comments"
                                              placeholder="Rédigez votre réponse...">{{ old('comments', $submission->content) }}</textarea>
                                    <div class="pdf-section">
                                        <span class="pdf-section-label"><i class="ti ti-paperclip"></i> Fichier — remplace le fichier actuel (optionnel)</span>
                                        @if($submission->attachments)
                                            @foreach((array)$submission->attachments as $attachment)
                                                <div class="current-file">
                                                    <i class="ti ti-file"></i>
                                                    Fichier actuel :
                                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank">{{ basename($attachment) }}</a>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="file-upload" onclick="document.getElementById('edit_submission_file').click()">
                                            <input type="file" id="edit_submission_file" name="submission_file"
                                                   accept=".pdf,.zip,.doc,.docx"
                                                   onchange="showEditFileName(this)">
                                            <i class="ti ti-upload"></i>
                                            Cliquez pour remplacer le fichier
                                            <div class="file-upload-hint">PDF, ZIP, DOC, DOCX · max 50 Mo</div>
                                        </div>
                                        <div id="edit-file-selected" class="selected-file" style="display:none;">
                                            <i class="ti ti-circle-check"></i>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning"><i class="ti ti-device-floppy"></i> Enregistrer les modifications</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endif

</div>
<script>
// PDF toggle function (always available)
function toggleTpPdf(btn) {
    const viewer  = document.getElementById(btn.dataset.target);
    const span    = btn.querySelector('span');
    const icon    = btn.querySelector('i');
    const open    = viewer.style.display === 'block';
    viewer.style.display = open ? 'none' : 'block';
    icon.className  = open ? 'ti ti-eye' : 'ti ti-eye-off';
    span.textContent = open ? 'Afficher' : 'Masquer';
}

// File name display for edit form (always available)
function showEditFileName(input) {
    const box  = document.getElementById('edit-file-selected');
    const span = box.querySelector('span');
    if (input.files && input.files[0]) {
        span.textContent = input.files[0].name;
        box.style.display = 'flex';
    } else {
        box.style.display = 'none';
    }
}
</script>
@endsection
