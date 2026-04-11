@extends('layouts.student')

@section('title', $tp->title)
@section('page-title', $tp->title)

@section('extra-styles')
<style>
    .btn { padding: 0.6rem 1.2rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
    .btn-secondary { background: #6c757d; color: white; }
    .btn-secondary:hover { background: #545b62; }
    .tp-info-card { background: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .tp-info-card h2 { color: #007bff; margin-top: 0; padding-bottom: 0.5rem; border-bottom: 2px solid #f0f0f0; margin-bottom: 1.5rem; }
    .info-row { display: grid; grid-template-columns: 200px 1fr; padding: 1rem 0; border-bottom: 1px solid #f0f0f0; }
    .info-label { font-weight: bold; color: #666; }
    .info-value { color: #333; }
    .submission-card { background: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .submission-card h2 { margin-top: 0; color: #28a745; border-bottom: 2px solid #f0f0f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; }
    .grade-display { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 8px; text-align: center; margin-bottom: 1.5rem; }
    .grade-number { font-size: 3rem; font-weight: bold; margin-bottom: 0.5rem; }
    .comment-box { background: #f8f9fa; padding: 1.5rem; border-left: 4px solid #007bff; border-radius: 4px; margin-top: 1rem; }
    .submit-form { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .submit-form h2 { margin-top: 0; color: #007bff; border-bottom: 2px solid #f0f0f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: bold; }
    .btn-submit { width: 100%; padding: 1rem; background: #28a745; color: white; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; margin-top: 1rem; }
    .btn-submit:hover { background: #218838; }
    .warning-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px; }
    .success-box { background: #d4edda; border-left: 4px solid #28a745; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px; color: #155724; }
    .error { color: #dc3545; font-size: 0.875rem; margin-top: 0.5rem; }

    /* Enonce box styles */
    .enonce-box { border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
    .enonce-box textarea {
        border: none;
        border-bottom: 1px solid #eee;
        border-radius: 0;
        margin: 0;
        width: 100%;
        padding: 0.75rem;
        min-height: 120px;
        resize: vertical;
        font-size: 1rem;
        font-family: inherit;
    }
    .enonce-box textarea:focus { outline: none; box-shadow: none; }
    .pdf-section { padding: 1rem; background: #fafafa; }
    .pdf-section-label { font-size: 0.85rem; font-weight: bold; color: #555; margin-bottom: 0.75rem; display: block; }
    .file-upload {
        border: 2px dashed #ccc;
        padding: 1rem;
        text-align: center;
        border-radius: 4px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.9rem;
        color: #666;
    }
    .file-upload:hover { background: #e7f3ff; border-color: #007bff; color: #007bff; }
    .file-upload input[type="file"] { display: none; }
    .selected-file {
        margin-top: 0.5rem;
        padding: 0.4rem 0.75rem;
        background: #d4edda;
        border-left: 3px solid #28a745;
        border-radius: 4px;
        font-size: 0.85rem;
    }
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
                    Pas de date limite
                @endif
            </div>
        </div>
        @if($tp->attachments)
            <div class="info-row">
                <div class="info-label">Énoncé PDF:</div>
                <div class="info-value">
                    <a href="{{ asset('storage/' . $tp->attachments) }}" target="_blank" style="color: #007bff;">
                        📎 Télécharger l'énoncé
                    </a>
                </div>
            </div>
        @endif
    </div>

    @if($tp->due_date && now()->lt($tp->due_date))
    <div id="sticky-countdown" style="
        position: fixed; bottom: 2rem; right: 2rem;
        background: #fef08a; border-radius: 4px 4px 4px 0;
        padding: 1.25rem 1.5rem;
        box-shadow: 3px 3px 10px rgba(0,0,0,0.15), -1px -1px 0 #e9d835 inset;
        max-width: 200px; z-index: 99;
        font-family: 'Comic Sans MS', cursive, sans-serif;
        transform: rotate(2deg); cursor: grab; user-select: none;
    ">
        <div id="sticky-hide" style="
            position: absolute; top: 4px; right: 6px;
            font-size: 0.75rem; color: #92400e; cursor: pointer;
            font-weight: bold; line-height: 1; padding: 2px 4px; border-radius: 3px;
        " title="Masquer">✕</div>

        <div style="
            width: 14px; height: 14px;
            background: radial-gradient(circle at 40% 35%, #ff6b6b, #c0392b);
            border-radius: 50%; position: absolute; top: -7px; left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.3); pointer-events: none;
        "></div>

        <div style="font-size: 0.75rem; color: #92400e; font-weight: bold; margin-bottom: 0.5rem; text-align: center;">
            ⏰ Temps restant
        </div>

        <div id="countdown-display" style="text-align: center; color: #1e3a5f;">
            <div style="font-size: 1.4rem; font-weight: bold;" id="cd-days">--</div>
            <div style="font-size: 0.65rem; color: #555; margin-bottom: 0.5rem;">jours</div>
            <div style="display: flex; justify-content: center; gap: 0.4rem; font-size: 1.1rem; font-weight: bold;">
                <span id="cd-hours">--</span>
                <span style="color:#aaa;">:</span>
                <span id="cd-mins">--</span>
                <span style="color:#aaa;">:</span>
                <span id="cd-secs">--</span>
            </div>
            <div style="display: flex; justify-content: center; gap: 0.75rem; font-size: 0.6rem; color: #555; margin-top: 0.2rem;">
                <span>h</span><span>min</span><span>sec</span>
            </div>
        </div>

        <div style="font-size: 0.7rem; color: #92400e; text-align: center; margin-top: 0.75rem; border-top: 1px dashed #d97706; padding-top: 0.5rem;">
            📅 {{ $tp->due_date->format('d/m/Y à H:i') }}
        </div>
    </div>

    <div id="sticky-show" style="
        display: none; position: fixed; bottom: 2rem; right: 2rem;
        background: #fef08a; border-radius: 50%;
        width: 44px; height: 44px;
        box-shadow: 3px 3px 10px rgba(0,0,0,0.2);
        z-index: 99; cursor: pointer; font-size: 1.2rem;
        text-align: center; line-height: 44px; border: 2px solid #e9d835;
    " title="Afficher le compte à rebours">⏰</div>

    <script>
        const note    = document.getElementById('sticky-countdown');
        const hideBtn = document.getElementById('sticky-hide');
        const showBtn = document.getElementById('sticky-show');

        hideBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            note.style.display    = 'none';
            showBtn.style.display = 'block';
        });
        showBtn.addEventListener('click', () => {
            note.style.display    = 'block';
            showBtn.style.display = 'none';
        });

        let isDragging = false, offsetX = 0, offsetY = 0;
        note.addEventListener('mousedown', (e) => {
            if (e.target === hideBtn) return;
            isDragging = true;
            offsetX = e.clientX - note.getBoundingClientRect().left;
            offsetY = e.clientY - note.getBoundingClientRect().top;
            note.style.cursor    = 'grabbing';
            note.style.transform = 'rotate(0deg) scale(1.02)';
            note.style.right     = 'auto';
            note.style.bottom    = 'auto';
        });
        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            note.style.left = (e.clientX - offsetX) + 'px';
            note.style.top  = (e.clientY - offsetY) + 'px';
        });
        document.addEventListener('mouseup', () => {
            if (!isDragging) return;
            isDragging           = false;
            note.style.cursor    = 'grab';
            note.style.transform = 'rotate(2deg)';
        });

        const deadline = new Date("{{ $tp->due_date->toIso8601String() }}");
        function updateCountdown() {
            const diff = deadline - new Date();
            if (diff <= 0) {
                document.getElementById('countdown-display').innerHTML =
                    `<div style="color:#dc2626;font-weight:bold;font-size:0.9rem;">⚠️ Échéance dépassée !</div>`;
                return;
            }
            const days  = Math.floor(diff / 86400000);
            const hours = Math.floor((diff % 86400000) / 3600000);
            const mins  = Math.floor((diff % 3600000) / 60000);
            const secs  = Math.floor((diff % 60000) / 1000);
            document.getElementById('cd-days').textContent  = days;
            document.getElementById('cd-hours').textContent = String(hours).padStart(2,'0');
            document.getElementById('cd-mins').textContent  = String(mins).padStart(2,'0');
            document.getElementById('cd-secs').textContent  = String(secs).padStart(2,'0');
            if (diff < 86400000) {
                note.style.background = '#fecaca';
                note.style.boxShadow  = '3px 3px 10px rgba(0,0,0,0.15), -1px -1px 0 #f87171 inset';
            }
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
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
                <div class="info-label">Fichier soumis:</div>
                <div class="info-value">
                    <a href="{{ asset('storage/' . $submission->attachments) }}" target="_blank" style="color: #007bff;">
                        📥 Télécharger mon fichier
                    </a>
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

        @if($submission->status === 'late')
            <div class="warning-box">⚠️ Soumission en retard</div>
        @endif

        {{-- Show edit button only if not graded and deadline not passed --}}
        @if(!$submission->grade && (!$tp->due_date || now()->lt($tp->due_date)))
            <button onclick="document.getElementById('edit-form').style.display='block'; this.style.display='none';"
                    style="margin-top:1rem; padding:0.75rem 1.5rem; background:#ffc107; color:#333; border:none; border-radius:4px; cursor:pointer; font-weight:bold;">
                ✏️ Modifier ma soumission
            </button>
        @endif
    </div>

    {{-- Edit form (hidden by default) --}}
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
                                <div class="file-upload" onclick="document.getElementById('submission_file').click()">
                                    <input type="file"
                                           id="submission_file"
                                           name="submission_file"
                                           accept=".pdf,.zip,.doc,.docx"
                                           onchange="showFileName(this)">
                                    📎 Cliquez pour sélectionner un fichier
                                    <div style="font-size: 0.8rem; margin-top: 0.25rem; color: #999;">
                                        PDF, ZIP, DOC, DOCX uniquement · max 10 Mo
                                    </div>
                                </div>
                                <div id="file-selected" class="selected-file" style="display: none;"></div>
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

@section('extra-scripts')
<script>
function showFileName(input) {
    const fileSelected = document.getElementById('file-selected');
    if (input.files && input.files[0]) {
        fileSelected.style.display = 'block';
        fileSelected.innerHTML = '✓ Fichier sélectionné: ' + input.files[0].name;
    } else {
        fileSelected.style.display = 'none';
    }
}
</script>
@endsection