@extends('layouts.app')

@section('title', 'Accueil')
@section('page-title', 'Fil d\'actualité')
@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.dashboard') }}
@endsection

@section('extra-styles')
<style>
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: #cbd5e1; font-weight: bold; }
    input[type="text"], textarea, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #475569;
        border-radius: 6px;
        font-size: 1rem;
        background: #1e293b;
        color: #e2e8f0;
    }
    input[type="text"]::placeholder,
    textarea::placeholder { color: #64748b; }
    select option { background: #1e293b; color: #e2e8f0; }
    textarea { min-height: 120px; resize: vertical; }
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #6366f1;
    }
    .btn-post {
        background: #4f46e5;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-post:hover { background: #4338ca; }
    .error { color: #fca5a5; font-size: 0.875rem; margin-top: 0.5rem; }

    #open-post-modal:hover { background: #4338ca !important; }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
        gap: 0.25rem;
    }
    .page-link {
        color: #cbd5e1;
        background: #0f172a;
        border: 1px solid #334155;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .page-link:hover { background: #1e293b; color: #e2e8f0; border-color: #475569; }
    .page-item.active .page-link { background: #4f46e5; color: white; border-color: #4f46e5; }
    .page-item.disabled .page-link { color: #64748b; background: #0f172a; border-color: #334155; cursor: not-allowed; }

    .breadcrumb { background: transparent; margin-bottom: 1rem; padding: 0; }
    .breadcrumb-item { color: #94a3b8; }
    .breadcrumb-item a { color: #cbd5e1; text-decoration: none; }
    .breadcrumb-item a:hover { color: #e2e8f0; }
    .breadcrumb-item.active { color: #e2e8f0; font-weight: bold; }
    .breadcrumb-item + .breadcrumb-item::before { color: #64748b; content: "/"; }

    .post-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid #334155;
    }
    .like-btn {
        background: none;
        border: none;
        padding: 0.25rem 0.4rem;
        cursor: pointer;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        transition: color 0.15s;
    }
    .like-btn:hover { color: #e2137a; }
    .like-btn:hover .like-icon { transform: scale(1.3); }
    .like-btn.liked { color: #e2137a; }
    .like-btn.liked .like-icon { transform: scale(1.15); }
    .like-icon { transition: transform 0.15s; display: inline-block; }

    .comment-count-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        color: #94a3b8;
        text-decoration: none;
        transition: color 0.15s;
    }
    .comment-count-link:hover { color: #6366f1; }

    .post-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 600px;
    }
</style>
@endsection

@section('content')

{{-- New post button --}}
<div style="display:flex; justify-content:flex-end; margin-bottom:1.5rem;">
    <button type="button" id="open-post-modal"
            style="background:#4f46e5; color:white; padding:0.65rem 1.4rem; border:none;
                   border-radius:8px; font-size:0.95rem; font-weight:bold; cursor:pointer;
                   display:flex; align-items:center; gap:0.5rem; transition:background 0.2s;">
        ✏️ Nouvelle publication
    </button>
</div>

{{-- Modal backdrop --}}
<div id="post-modal-backdrop"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6);
            z-index:200; align-items:center; justify-content:center; padding:1rem;">

    {{-- Modal box --}}
    <div style="background:#0f172a; border:1px solid #334155; border-radius:14px;
                width:100%; max-width:680px; max-height:90vh; overflow-y:auto;
                padding:2rem; position:relative;">

        {{-- Close button --}}
        <button type="button" id="close-post-modal"
                style="position:absolute; top:1rem; right:1rem; background:#1e293b;
                       border:1px solid #334155; color:#94a3b8; width:32px; height:32px;
                       border-radius:6px; cursor:pointer; font-size:1.1rem;
                       display:flex; align-items:center; justify-content:center;">✕</button>

        <h2 style="margin-top:0; color:#f1f5f9; border-bottom:1px solid #334155;
                   padding-bottom:0.5rem; margin-bottom:1.5rem;">✍️ Créer une publication</h2>

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Type --}}
            <div class="form-group">
                <label for="type">Type de publication *</label>
                <select id="type" name="type" required>
                    <option value="announcement">📢 Annonce importante</option>
                    <option value="reminder">⏰ Rappel</option>
                    <option value="general">📌 Publication générale</option>
                </select>
                @error('type')<div class="error">{{ $message }}</div>@enderror
            </div>

            {{-- Title --}}
            <div class="form-group">
                <label for="title">Titre *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                       placeholder="Ex: Rappel - TP à rendre vendredi"
                       maxlength="100" required>
                <div style="text-align:right; font-size:0.78rem; margin-top:0.25rem; color:#64748b;" id="title-counter">0 / 100</div>
                @error('title')<div class="error">{{ $message }}</div>@enderror
            </div>

            {{-- Courses multi-select dropdown --}}
            <div class="form-group">
                <label>Cours</label>
                <div style="position:relative;">
                    <button type="button" id="courses-trigger"
                            style="width:100%; padding:0.75rem; background:#1e293b; border:1px solid #475569;
                                   border-radius:6px; color:#e2e8f0; text-align:left; cursor:pointer;
                                   font-size:1rem; display:flex; justify-content:space-between; align-items:center;">
                        <span id="courses-trigger-label">🌍 Tous mes étudiants (publication générale)</span>
                        <span id="courses-chevron" style="transition:transform 0.2s;">▼</span>
                    </button>

                    <div id="courses-panel"
                         style="display:none; position:absolute; top:calc(100% + 4px); left:0; right:0;
                                background:#1e293b; border:1px solid #475569; border-radius:8px;
                                z-index:250; box-shadow:0 8px 24px rgba(0,0,0,0.4); overflow:hidden;">
                        <div style="padding:0.5rem;">
                            <input type="text" id="courses-search"
                                   placeholder="🔍 Rechercher un cours..."
                                   style="width:100%; padding:0.5rem 0.75rem; background:#0f172a;
                                          border:1px solid #334155; border-radius:6px;
                                          color:#e2e8f0; font-size:0.9rem;">
                        </div>
                        <div style="max-height:200px; overflow-y:auto; padding:0.25rem 0.5rem 0.5rem;">
                            <label id="opt-all"
                                   style="display:flex; align-items:center; gap:0.6rem; padding:0.5rem 0.6rem;
                                          border-radius:6px; cursor:pointer; border-bottom:1px solid #334155;
                                          margin-bottom:0.25rem;">
                                <input type="checkbox" id="course-all"
                                       style="width:15px;height:15px;accent-color:#6366f1;flex-shrink:0;">
                                <span style="color:#a78bfa; font-weight:500;">🌍 Tous mes étudiants</span>
                            </label>
                            @foreach($courses as $course)
                                <label class="course-opt"
                                       style="display:flex; align-items:center; gap:0.6rem; padding:0.5rem 0.6rem;
                                              border-radius:6px; cursor:pointer;"
                                       data-name="{{ strtolower($course->name) }}">
                                    <input type="checkbox" name="class_ids[]" value="{{ $course->id }}"
                                           class="course-cb"
                                           style="width:15px;height:15px;accent-color:#6366f1;flex-shrink:0;"
                                           {{ is_array(old('class_ids')) && in_array($course->id, old('class_ids')) ? 'checked' : '' }}>
                                    <span style="color:#cbd5e1; font-size:0.9rem;">
                                        📚 {{ $course->name }}
                                        <span style="color:#64748b; font-size:0.8rem;">({{ $course->students->count() }} étudiants)</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @error('class_ids')<div class="error">{{ $message }}</div>@enderror
            </div>

            {{-- Content --}}
            <div class="form-group">
                <label for="content">Contenu *</label>
                <textarea id="content" name="content" required
                          maxlength="2000"
                          placeholder="Écrivez votre message...">{{ old('content') }}</textarea>
                <div style="text-align:right; font-size:0.78rem; margin-top:0.25rem; color:#64748b;" id="content-counter">0 / 2000</div>
                @error('content')<div class="error">{{ $message }}</div>@enderror
            </div>

            {{-- Attachment --}}
            <div class="form-group">
                <label>Pièce jointe (optionnel)</label>
                <x-file-upload id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.zip" hint="PDF, JPG, PNG, ZIP · max 10 Mo" />
                @error('attachment')<div class="error">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-post">📤 Publier</button>
        </form>
    </div>
</div>

{{-- Feed --}}
<div class="feed-section">
    <h2 style="margin-bottom: 1.5rem; color: #f1f5f9;">📰 Mes publications</h2>

    @forelse($posts as $post)
        <div class="post-card"
             style="cursor:pointer;"
             onclick="if(event.target.closest('form, a, button')) return; window.location='{{ route('posts.show', $post->id) }}'">

            <div class="post-header">
                <div style="display:flex; gap:1rem; align-items:flex-start; flex:1;">
                    <img src="{{ $post->user->profile_picture_url }}"
                         alt="{{ $post->user->name }}"
                         style="width:44px;height:44px;border-radius:50%;object-fit:cover;flex-shrink:0;">

                    <div class="post-info">
                        <span class="post-type-badge type-{{ $post->type }}">
                            @if($post->type === 'announcement') 📢 Annonce
                            @elseif($post->type === 'tp_posted') 📝 TP
                            @elseif($post->type === 'reminder') ⏰ Rappel
                            @else 📌 Général
                            @endif
                        </span>
                        <div class="post-title">{{ $post->title }}</div>
                        <div class="post-meta">Publié {{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <div class="post-content" style="white-space: pre-line;">{{ $post->content }}</div>

            @if($post->tp && $post->tp->due_date)
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #334155; color: #cbd5e1; font-size: 0.9rem;">
                    📅 Échéance: {{ $post->tp->due_date->format('d/m/Y à H:i') }}
                </div>
            @endif

            @if($post->class)
                <div class="post-course">📚 {{ $post->class->name }} ({{ $post->class->students->count() }} étudiants)</div>
            @else
                <div class="post-course">🌍 Publication générale</div>
            @endif

            @if($post->tp)
                <div style="margin-top: 1rem;">
                    <a href="{{ route('teacher.tps.show', $post->tp->id) }}" class="attachment-btn">👁️ Voir le TP</a>
                </div>
            @endif

            @if($post->attachment)
                <div class="post-attachment">
                    <a href="{{ asset('storage/' . $post->attachment) }}" target="_blank" class="attachment-btn">
                        📎 Télécharger la pièce jointe
                    </a>
                </div>
            @endif

            <div class="post-actions">
                <button
                    class="like-btn {{ $post->isLikedBy(auth()->id()) ? 'liked' : '' }}"
                    data-type="post"
                    data-id="{{ $post->id }}">
                    <span class="like-icon">{{ $post->isLikedBy(auth()->id()) ? '❤️' : '🤍' }}</span>
                    <span class="like-count">{{ $post->likes()->count() }}</span>
                </button>

                <a href="{{ route('posts.show', $post->id) }}#comments" class="comment-count-link">
                    💬 {{ $post->comments->reduce(fn($carry, $c) => $carry + 1 + $c->replies->count(), 0) }}
                </a>
            </div>

        </div>
    @empty
        <div class="no-posts">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
            <h3 style="color: #94a3b8;">Aucune publication</h3>
            <p style="margin-top: 0.5rem;">Créez votre première publication pour communiquer avec vos étudiants</p>
        </div>
    @endforelse

    @if($posts->hasPages())
        <div style="margin-top: 1.5rem;">
            {{ $posts->links() }}
        </div>
    @endif
</div>

@endsection

@section('extra-scripts')
<script>
// ── Modal ──
const modal    = document.getElementById('post-modal-backdrop');
const openBtn  = document.getElementById('open-post-modal');
const closeBtn = document.getElementById('close-post-modal');

function openModal() {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

openBtn.addEventListener('click', openModal);
closeBtn.addEventListener('click', closeModal);

// Close on backdrop click
modal.addEventListener('click', e => {
    if (e.target === modal) closeModal();
});

// Close on Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});

// Auto-open if there were validation errors so user sees their mistakes
@if($errors->any())
    openModal();
@endif

// ── Courses dropdown ──
(function () {
    const trigger   = document.getElementById('courses-trigger');
    const panel     = document.getElementById('courses-panel');
    const chevron   = document.getElementById('courses-chevron');
    const label     = document.getElementById('courses-trigger-label');
    const allCb     = document.getElementById('course-all');
    const search    = document.getElementById('courses-search');
    const courseCbs = () => document.querySelectorAll('.course-cb');

    trigger.addEventListener('click', () => {
        const open = panel.style.display === 'block';
        panel.style.display = open ? 'none' : 'block';
        chevron.style.transform = open ? '' : 'rotate(180deg)';
        if (!open) search.focus();
    });

    // Close courses panel on outside click
    document.addEventListener('click', e => {
        if (!e.target.closest('#courses-trigger') && !e.target.closest('#courses-panel')) {
            panel.style.display = 'none';
            chevron.style.transform = '';
        }
    });

    search.addEventListener('input', () => {
        const q = search.value.toLowerCase();
        document.querySelectorAll('.course-opt').forEach(opt => {
            opt.style.display = opt.dataset.name.includes(q) ? '' : 'none';
        });
    });

    function updateLabel() {
        const checked = [...courseCbs()].filter(cb => cb.checked);
        if (checked.length === 0 || allCb.checked) {
            label.textContent = '🌍 Tous mes étudiants (publication générale)';
        } else if (checked.length === 1) {
            label.textContent = checked[0].closest('label').querySelector('span').textContent.trim();
        } else {
            label.textContent = '📚 ' + checked.length + ' cours sélectionnés';
        }
    }

    allCb.addEventListener('change', () => {
        if (allCb.checked) courseCbs().forEach(cb => cb.checked = false);
        updateLabel();
    });

    document.addEventListener('change', e => {
        if (e.target.classList.contains('course-cb')) {
            if (e.target.checked) allCb.checked = false;
            updateLabel();
        }
    });

    updateLabel();
})();

// ── Char counters ──
function makeCounter(inputId, counterId, max) {
    const input   = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    function update() {
        const len = input.value.length;
        counter.textContent = len + ' / ' + max;
        counter.style.color = len >= max ? '#ef4444' : len >= max * 0.85 ? '#f59e0b' : '#64748b';
    }
    input.addEventListener('input', update);
    update();
}
makeCounter('title',   'title-counter',   100);
makeCounter('content', 'content-counter', 2000);

// ── Likes ──
document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id  = btn.dataset.id;
        const res = await fetch(`/posts/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        const data = await res.json();
        btn.querySelector('.like-icon').textContent = data.liked ? '❤️' : '🤍';
        btn.querySelector('.like-count').textContent = data.count;
        btn.classList.toggle('liked', data.liked);
    });
});
</script>
@endsection