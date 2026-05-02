@extends('layouts.app')
@section('title', Str::limit($tp->title, 50))

@section('content')

{{-- TP Details --}}
<div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-6 mb-6">
    <h2 class="text-base font-bold text-blue-600 dark:text-blue-400 pb-3 mb-4
               border-b border-slate-200 dark:border-slate-600">
        📝 Détails du TP
    </h2>
    @foreach([
        ['Cours',        $tp->class->name],
        ['Enseignant',   $tp->teacher->name],
        ['Titre',        $tp->title],
        ['Description',  $tp->description ?? '—'],
    ] as [$label, $value])
        <div class="grid grid-cols-[160px_1fr] py-3 border-b border-slate-200 dark:border-slate-600 last:border-0">
            <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $label }}:</div>
            <div class="text-sm text-slate-800 dark:text-slate-200 break-words">{{ $value }}</div>
        </div>
    @endforeach
    <div class="grid grid-cols-[160px_1fr] py-3 border-b border-slate-200 dark:border-slate-600">
        <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Date limite:</div>
        <div class="text-sm text-slate-800 dark:text-slate-200">
            @if($tp->due_date)
                {{ $tp->due_date->format('d/m/Y à H:i') }}
                @if(now()->gt($tp->due_date))
                    <span class="text-red-500 dark:text-red-400 font-bold ml-2">(Échéance dépassée)</span>
                @endif
            @else
                Pas d'échéance définie
            @endif
        </div>
    </div>
    @if($tp->attachments)
        <div class="grid grid-cols-[160px_1fr] py-3">
            <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Énoncé PDF:</div>
            <div>
                <a href="{{ asset('storage/' . $tp->attachments) }}" target="_blank"
                   class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    📎 Télécharger l'énoncé
                </a>
            </div>
        </div>
    @endif
</div>

{{-- Countdown sticky note (preserved as-is, it's purely cosmetic/JS) --}}
@if($tp->due_date && now()->lt($tp->due_date))
<div id="sticky-countdown" style="
    position:fixed; bottom:2rem; right:2rem;
    background:#fef08a; border-radius:4px 4px 4px 0;
    padding:1.25rem 1.5rem;
    box-shadow:3px 3px 10px rgba(0,0,0,0.15),-1px -1px 0 #e9d835 inset;
    max-width:200px; z-index:99;
    font-family:'Comic Sans MS',cursive,sans-serif;
    transform:rotate(2deg); cursor:grab; user-select:none;">
    <div id="sticky-hide" style="position:absolute;top:4px;right:6px;font-size:0.75rem;color:#92400e;cursor:pointer;font-weight:bold;padding:2px 4px;border-radius:3px;" title="Masquer">✕</div>
    <div style="width:14px;height:14px;background:radial-gradient(circle at 40% 35%,#ff6b6b,#c0392b);border-radius:50%;position:absolute;top:-7px;left:50%;transform:translateX(-50%);box-shadow:0 2px 4px rgba(0,0,0,0.3);pointer-events:none;"></div>
    <div style="font-size:0.75rem;color:#92400e;font-weight:bold;margin-bottom:0.5rem;text-align:center;">⏰ Temps restant</div>
    <div id="countdown-display" style="text-align:center;color:#1e3a5f;">
        <div style="font-size:1.4rem;font-weight:bold;" id="cd-days">--</div>
        <div style="font-size:0.65rem;color:#555;margin-bottom:0.5rem;">jours</div>
        <div style="display:flex;justify-content:center;gap:0.4rem;font-size:1.1rem;font-weight:bold;">
            <span id="cd-hours">--</span><span style="color:#aaa">:</span>
            <span id="cd-mins">--</span><span style="color:#aaa">:</span>
            <span id="cd-secs">--</span>
        </div>
        <div style="display:flex;justify-content:center;gap:0.75rem;font-size:0.6rem;color:#555;margin-top:0.2rem;">
            <span>h</span><span>min</span><span>sec</span>
        </div>
    </div>
    <div style="font-size:0.7rem;color:#92400e;text-align:center;margin-top:0.75rem;border-top:1px dashed #d97706;padding-top:0.5rem;">
        📅 {{ $tp->due_date->format('d/m/Y à H:i') }}
    </div>
</div>
<div id="sticky-show" style="display:none;position:fixed;bottom:2rem;right:2rem;background:#fef08a;border-radius:50%;width:44px;height:44px;box-shadow:3px 3px 10px rgba(0,0,0,0.2);z-index:99;cursor:pointer;font-size:1.2rem;text-align:center;line-height:44px;border:2px solid #e9d835;" title="Afficher">⏰</div>
@endif

{{-- Submit form (not yet submitted + deadline not passed) --}}
@if(!$submission && (!$tp->due_date || now()->lt($tp->due_date)))
    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-6 mb-6">
        <h2 class="text-base font-bold text-blue-600 dark:text-blue-400 pb-3 mb-5
                   border-b border-slate-200 dark:border-slate-600">
            📤 Soumettre votre travail
        </h2>
        <form method="POST" action="{{ route('student.tps.submit', $tp->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                    Commentaires / Réponse
                </label>
                <div class="rounded-lg border border-slate-300 dark:border-slate-600 overflow-hidden">
                    <textarea id="comments" name="comments" required rows="5"
                              placeholder="Rédigez votre réponse..."
                              class="w-full px-3 py-2.5 bg-white dark:bg-slate-700
                                     text-slate-900 dark:text-slate-100
                                     placeholder-slate-400 dark:placeholder-slate-500
                                     focus:outline-none resize-y border-0 border-b border-slate-300 dark:border-slate-600">{{ old('comments') }}</textarea>
                    <div class="bg-slate-100 dark:bg-slate-800 px-3 py-3">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-2">📎 Joindre un fichier (optionnel)</span>
                        <x-file-upload id="submission_file" name="submission_file"
                                       accept=".pdf,.zip,.doc,.docx"
                                       hint="PDF, ZIP, DOC, DOCX uniquement · max 10 Mo" />
                    </div>
                </div>
                @error('comments')
                    <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit"
                    class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg
                           font-bold text-base transition-colors">
                📤 Soumettre mon travail
            </button>
        </form>
    </div>
@endif

{{-- Submitted --}}
@if($submission)
    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-6 mb-6">
        <h2 class="text-base font-bold text-green-600 dark:text-green-400 pb-3 mb-5
                   border-b border-slate-200 dark:border-slate-600">
            ✅ Votre Soumission
        </h2>

        @if($submission->grade)
            <div class="bg-violet-600 text-white rounded-xl p-6 text-center mb-5">
                <div class="text-sm mb-1 opacity-90">Votre note</div>
                <div class="text-5xl font-bold">{{ $submission->grade }}/20</div>
            </div>
            @if($submission->teacher_comment)
                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500
                            px-4 py-3 rounded-lg mb-4">
                    <strong class="text-sm text-blue-700 dark:text-blue-300">💬 Commentaire de l'enseignant:</strong>
                    <p class="text-sm text-slate-700 dark:text-slate-300 mt-1 mb-0">{{ $submission->teacher_comment }}</p>
                </div>
            @endif
        @else
            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500
                        px-4 py-3 rounded-lg text-sm text-green-700 dark:text-green-300 mb-4">
                ✓ Votre travail a été soumis avec succès le {{ $submission->submitted_at->format('d/m/Y à H:i') }}
            </div>
        @endif

        @if($submission->attachments)
            <div class="grid grid-cols-[160px_1fr] py-3 border-b border-slate-200 dark:border-slate-600">
                <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Fichier soumis:</div>
                <div>
                    <a href="{{ asset('storage/' . $submission->attachments) }}" target="_blank"
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        📥 Télécharger mon fichier
                    </a>
                </div>
            </div>
        @endif
        @if($submission->content)
            <div class="grid grid-cols-[160px_1fr] py-3 border-b border-slate-200 dark:border-slate-600">
                <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Vos commentaires:</div>
                <div class="text-sm text-slate-700 dark:text-slate-300">{{ $submission->content }}</div>
            </div>
        @endif
        <div class="grid grid-cols-[160px_1fr] py-3 {{ $submission->status === 'late' ? 'border-b border-slate-200 dark:border-slate-600' : '' }}">
            <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">Date de soumission:</div>
            <div class="text-sm text-slate-700 dark:text-slate-300">{{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
        </div>
        @if($submission->status === 'late')
            <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500
                        px-4 py-3 rounded-lg text-sm text-amber-700 dark:text-amber-300 mt-3">
                ⚠️ Soumission en retard
            </div>
        @endif

        @if(!$submission->grade && (!$tp->due_date || now()->lt($tp->due_date)))
            <button onclick="document.getElementById('edit-form').classList.remove('hidden'); this.classList.add('hidden')"
                    class="mt-4 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg
                           font-bold text-sm transition-colors">
                ✏️ Modifier ma soumission
            </button>
        @endif
    </div>

<<<<<<< HEAD
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
=======
    @if(!$submission->grade && (!$tp->due_date || now()->lt($tp->due_date)))
        <div id="edit-form" class="hidden bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 p-6">
            <h2 class="text-base font-bold text-blue-600 dark:text-blue-400 pb-3 mb-5
                       border-b border-slate-200 dark:border-slate-600">
                ✏️ Modifier votre Soumission
            </h2>
            <form method="POST" action="{{ route('student.tps.update-submission', $tp->id) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Commentaires / Réponse
                    </label>
                    <div class="rounded-lg border border-slate-300 dark:border-slate-600 overflow-hidden">
                        <textarea name="comments" rows="5"
>>>>>>> 29f2233 (fifth update)
                                  placeholder="Rédigez votre réponse..."
                                  class="w-full px-3 py-2.5 bg-white dark:bg-slate-700
                                         text-slate-900 dark:text-slate-100
                                         placeholder-slate-400 dark:placeholder-slate-500
                                         focus:outline-none resize-y border-0 border-b border-slate-300 dark:border-slate-600">{{ old('comments', $submission->content) }}</textarea>
                        <div class="bg-slate-100 dark:bg-slate-800 px-3 py-3">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-2">📎 Remplacer le fichier (optionnel)</span>
                            <x-file-upload id="submission_file_edit" name="submission_file"
                                           accept=".pdf,.zip,.doc,.docx"
                                           hint="PDF, ZIP, DOC, DOCX uniquement · max 10 Mo" />
                        </div>
                    </div>
                </div>
                <button type="submit"
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg
                               font-bold text-base transition-colors">
                    ✓ Enregistrer les modifications
                </button>
            </form>
        </div>
    @endif
@endif

<<<<<<< HEAD
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
                        <a href="{{ asset('storage/' . $submission->attachments) }}" target="_blank" style="color: #3b82f6;">
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
                                    <x-file-upload id="submission_file" name="submission_file" accept=".pdf,.zip,.doc,.docx" hint="PDF, ZIP, DOC, DOCX uniquement · max 10 Mo" />
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">✓ Enregistrer les modifications</button>
                    </form>
                </div>
            </div>
        @endif
    @endif
=======
@endsection
>>>>>>> 29f2233 (fifth update)

@section('extra-scripts')
@if($tp->due_date && now()->lt($tp->due_date))
<script>
const note    = document.getElementById('sticky-countdown');
const hideBtn = document.getElementById('sticky-hide');
const showBtn = document.getElementById('sticky-show');
hideBtn.addEventListener('click', (e) => { e.stopPropagation(); note.style.display='none'; showBtn.style.display='block'; });
showBtn.addEventListener('click', () => { note.style.display='block'; showBtn.style.display='none'; });
let isDragging=false, offsetX=0, offsetY=0;
note.addEventListener('mousedown',(e)=>{ if(e.target===hideBtn) return; isDragging=true; offsetX=e.clientX-note.getBoundingClientRect().left; offsetY=e.clientY-note.getBoundingClientRect().top; note.style.cursor='grabbing'; note.style.transform='rotate(0deg) scale(1.02)'; note.style.right='auto'; note.style.bottom='auto'; });
document.addEventListener('mousemove',(e)=>{ if(!isDragging) return; note.style.left=(e.clientX-offsetX)+'px'; note.style.top=(e.clientY-offsetY)+'px'; });
document.addEventListener('mouseup',()=>{ if(!isDragging) return; isDragging=false; note.style.cursor='grab'; note.style.transform='rotate(2deg)'; });
const deadline = new Date("{{ $tp->due_date->toIso8601String() }}");
function updateCountdown() {
    const diff = deadline - new Date();
    if (diff <= 0) { document.getElementById('countdown-display').innerHTML='<div style="color:#dc2626;font-weight:bold;font-size:0.9rem;">⚠️ Échéance dépassée !</div>'; return; }
    const days=Math.floor(diff/86400000), hours=Math.floor((diff%86400000)/3600000), mins=Math.floor((diff%3600000)/60000), secs=Math.floor((diff%60000)/1000);
    document.getElementById('cd-days').textContent=days;
    document.getElementById('cd-hours').textContent=String(hours).padStart(2,'0');
    document.getElementById('cd-mins').textContent=String(mins).padStart(2,'0');
    document.getElementById('cd-secs').textContent=String(secs).padStart(2,'0');
    if (diff < 86400000) { note.style.background='#fecaca'; note.style.boxShadow='3px 3px 10px rgba(0,0,0,0.15),-1px -1px 0 #f87171 inset'; }
}
updateCountdown(); setInterval(updateCountdown, 1000);
</script>
@endif
@endsection