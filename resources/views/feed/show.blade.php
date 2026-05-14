@extends('layouts.app')

@section('title', $post->title)
@section('page-title', 'Publication')

@section('breadcrumbs')
    {{ Breadcrumbs::render('posts.show', $post) }}
@endsection

@section('extra-styles')
<style>
.post-title { font-size: 1.8rem; word-break: break-word; }
.post-content { font-size: 1.05rem; }

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
    transition: all 0.15s;
}
.like-btn:hover { color: #e2137a; }
.like-btn:hover .like-icon { transform: scale(1.3); }
.like-btn.liked { color: #e2137a; }
.like-btn.liked .like-icon { transform: scale(1.15); }
.like-btn:active { transform: scale(0.9); }
.like-icon { transition: transform 0.15s; display: inline-block; }

.replies {
    margin-top: 1rem;
    margin-left: 1rem;
    padding-left: 1rem;
    border-left: 3px solid #334155;
}
.reply {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.reply-form { display: none; margin-top: 0.75rem; }
.reply-form.active { display: block; }

.add-comment-form {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #334155;
}
.comment-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #475569;
    border-radius: 6px;
    background: #0f172a;
    color: #e2e8f0;
    resize: vertical;
    font-family: inherit;
    font-size: 0.95rem;
}
.comment-input:focus {
    outline: none;
    border-color: #6366f1;
}
.comment-input::placeholder { color: #64748b; }

.submit-comment-btn {
    margin-top: 0.75rem;
    padding: 0.6rem 1.5rem;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.2s;
}
.submit-comment-btn:hover { background: #4338ca; }

.cancel-reply-btn {
    margin-left: 0.5rem;
    padding: 0.6rem 1rem;
    background: #334155;
    color: #cbd5e1;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.2s;
}
.cancel-reply-btn:hover { background: #475569; }

/* 3-dots menu */
.post-menu-btn {
    background: transparent;
    border: 1px solid #334155;
    color: #94a3b8;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
}
.post-menu-btn:hover {
    background: #1e293b;
    border-color: #475569;
    color: #e2e8f0;
}
.post-menu-dropdown {
    display: none;
    position: absolute;
    top: 2.2rem;
    right: 0;
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 0.75rem;
    min-width: 150px;
    z-index: 100;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    overflow: hidden;
}
.post-menu-dropdown button {
    width: 100%;
    text-align: left;
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    cursor: pointer;
    border-radius: 0;
    font-size: 0.875rem;
    transition: background 0.15s;
}
.post-menu-dropdown .menu-edit-btn { color: #a5b4fc; }
.post-menu-dropdown .menu-edit-btn:hover { background: #334155; }
.post-menu-dropdown .menu-delete-btn { color: #fca5a5; }
.post-menu-dropdown .menu-delete-btn:hover { background: #334155; }

/* Edit modal */
.edit-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 999;
    align-items: center;
    justify-content: center;
}
.edit-modal-overlay.active {
    display: flex;
}
.edit-modal {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 12px;
    padding: 2rem;
    width: 100%;
    max-width: 620px;
    margin: 1rem;
    max-height: 90vh;
    overflow-y: auto;
}
.edit-modal h3 {
    color: #f1f5f9;
    margin-top: 0;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #334155;
}
.edit-modal label {
    display: block;
    color: #cbd5e1;
    margin-bottom: 0.5rem;
    font-weight: bold;
    font-size: 0.9rem;
}
.edit-modal input[type="text"],
.edit-modal textarea,
.edit-modal select {
    width: 100%;
    padding: 0.75rem;
    background: #0f172a;
    border: 1px solid #475569;
    border-radius: 6px;
    color: #e2e8f0;
    font-size: 1rem;
    font-family: inherit;
    box-sizing: border-box;
}
.edit-modal textarea { resize: vertical; }
.edit-modal input:focus,
.edit-modal textarea:focus,
.edit-modal select:focus {
    outline: none;
    border-color: #6366f1;
}
.edit-modal select option { background: #1e293b; }
.edit-modal-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}
.btn-cancel-edit {
    padding: 0.6rem 1.25rem;
    background: #334155;
    color: #cbd5e1;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.2s;
}
.btn-cancel-edit:hover { background: #475569; }
.btn-save-edit {
    padding: 0.6rem 1.5rem;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: bold;
    transition: background 0.2s;
}
.btn-save-edit:hover { background: #4338ca; }

.current-attachment-row {
    background: #0f172a;
    border: 1px solid #475569;
    border-radius: 6px;
    padding: 0.75rem 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.current-attachment-row span {
    color: #a5b4fc;
    font-size: 0.9rem;
    word-break: break-all;
}
.remove-attachment-label {
    display: inline-flex !important;
    align-items: center;
    gap: 0.4rem;
    cursor: pointer;
    color: #fca5a5 !important;
    font-size: 0.85rem !important;
    font-weight: normal !important;
    margin: 0 !important;
    white-space: nowrap;
    flex-shrink: 0;
}
.remove-attachment-label input[type="checkbox"] {
    width: auto !important;
    accent-color: #ef4444;
}

/* Comment highlight animation */
@keyframes highlightFade {
    0%   { background: #312e81; border-color: #6366f1; }
    100% { background: transparent; border-color: transparent; }
}
.comment-highlight {
    animation: highlightFade 2.5s ease-out forwards;
    border-radius: 8px;
    border: 1px solid transparent;
    padding: 0.5rem;
    margin: -0.5rem;
}
</style>
@endsection

@section('content')

<div class="post-card" style="position: relative;">

    {{-- 3-dots menu (teacher only, own posts) --}}
    @if(Auth::user()->isTeacher() && $post->user_id === Auth::id())
        <div style="position:absolute; top:1rem; right:1rem;">
            <button class="post-menu-btn" onclick="togglePostMenu()">⋮</button>
            <div class="post-menu-dropdown" id="post-menu">
                <button type="button" class="menu-edit-btn" onclick="openEditModal()">✏️ Modifier</button>
                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" style="display:block; width:100%;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="menu-delete-btn">🗑️ Supprimer</button>
                </form>
            </div>
        </div>
    @endif

    <span class="post-type-badge type-{{ $post->type }}">
        @if($post->type === 'announcement') 📢 Annonce
        @elseif($post->type === 'tp_posted') 📝 Nouveau TP
        @elseif($post->type === 'reminder') ⏰ Rappel
        @else 📌 Général
        @endif
    </span>

    <div class="post-title">{{ $post->title }}</div>
    <div class="post-meta">
        Par {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}
    </div>
    <div class="post-content" style="white-space: pre-line;">{{ $post->content }}</div>

    @if($post->tp && $post->tp->due_date)
        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #334155; color: #cbd5e1; font-size: 0.9rem;">
            📅 Échéance: {{ $post->tp->due_date->format('d/m/Y à H:i') }}
        </div>
    @endif

    @if($post->class)
        <div class="post-course">📚 {{ $post->class->name }}</div>
    @endif

    @if($post->tp)
        <div style="margin-top: 1rem;">
            @if(Auth::user()->isTeacher())
                <a href="{{ route('teacher.tps.show', $post->tp->id) }}" class="attachment-btn">👁️ Voir le TP</a>
            @else
                <a href="{{ route('student.tps.show', $post->tp->id) }}" class="attachment-btn">👁️ Voir le TP</a>
            @endif
        </div>
    @endif

    @if($post->attachment)
        <a href="{{ asset('storage/' . $post->attachment) }}" target="_blank" class="attachment-btn">
            📎 Télécharger la pièce jointe
        </a>
    @endif

    <div class="post-actions" style="margin-top: 1rem;">
        <button
            class="like-btn {{ $post->is_liked ? 'liked' : '' }}"
            data-type="post"
            data-id="{{ $post->id }}">
            
            <span class="like-icon">{{ $post->is_liked ? '❤️' : '🤍' }}</span>

            <span class="like-count">{{ $post->likes_count }}</span>
        </button>
    </div>
</div>

{{-- Edit Modal --}}
@if(Auth::user()->isTeacher() && $post->user_id === Auth::id())
<div class="edit-modal-overlay" id="edit-modal">
    <div class="edit-modal">
        <h3>✏️ Modifier la publication</h3>
        <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom:1.25rem;">
                <label>Type</label>
                <select name="type">
                    <option value="announcement" {{ $post->type === 'announcement' ? 'selected' : '' }}>📢 Annonce importante</option>
                    <option value="reminder"     {{ $post->type === 'reminder'     ? 'selected' : '' }}>⏰ Rappel</option>
                    <option value="general"      {{ $post->type === 'general'      ? 'selected' : '' }}>📌 Publication générale</option>
                </select>
            </div>

            <div style="margin-bottom:1.25rem;">
                <label>Titre *</label>
                <input type="text" name="title" value="{{ $post->title }}" maxlength="50" required>
            </div>

            <div style="margin-bottom:1.25rem;">
                <label>Contenu *</label>
                <textarea name="content" rows="6" required>{{ $post->content }}</textarea>
            </div>

            <div style="margin-bottom:1.25rem;">
                <label>Pièce jointe</label>

                @if($post->attachment)
                    <div class="current-attachment-row">
                        <span>📎 {{ basename($post->attachment) }}</span>
                        <label class="remove-attachment-label">
                            <input type="checkbox" name="remove_attachment" value="1">
                            🗑️ Supprimer
                        </label>
                    </div>
                @endif

                <x-file-upload id="edit-attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.zip"
                    hint="{{ $post->attachment ? 'Choisir un nouveau fichier remplacera l\'existant' : 'PDF, JPG, PNG, ZIP · max 10 Mo' }}" />
            </div>

            <div class="edit-modal-actions">
                <button type="button" class="btn-cancel-edit" onclick="closeEditModal()">Annuler</button>
                <button type="submit" class="btn-save-edit">💾 Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endif

<div class="comments-section" id="comments">
    <h3 style="margin-bottom: 1.5rem; color: #f1f5f9;">
        💬 Commentaires ({{ $post->comments->reduce(fn($carry, $c) => $carry + 1 + $c->replies->count(), 0) }})
    </h3>

    @forelse($post->comments as $comment)
        {{-- TOP-LEVEL COMMENT --}}
        <div class="comment" id="comment-{{ $comment->id }}">
            <img src="{{ $comment->user->profile_picture_url }}"
                 alt="{{ $comment->user->name }}"
                 style="width:40px;height:40px;min-width:40px;border-radius:50%;object-fit:cover;">

            <div class="comment-body">
                <div class="comment-header">
                    <span class="comment-author">{{ $comment->user->name }}</span>
                    <span class="comment-role {{ $comment->user->isTeacher() ? 'teacher' : '' }}">
                        {{ $comment->user->isTeacher() ? 'Enseignant' : 'Étudiant' }}
                    </span>
                    <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                </div>

                <div class="comment-content">{{ $comment->content }}</div>

                <div class="comment-actions">
                    <button class="reply-btn"
                            onclick="toggleReply('reply-form-{{ $comment->id }}', '{{ addslashes($comment->user->name) }}')">
                        ↩️ Répondre
                    </button>
                    @if($comment->user_id === Auth::id())
                        <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="delete-btn" onclick="return confirm('Supprimer ce commentaire?')">
                                🗑️ Supprimer
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Reply to top-level comment --}}
                <div class="reply-form" id="reply-form-{{ $comment->id }}">
                    <form method="POST" action="{{ route('posts.comments.store', $post->id) }}">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="content"
                                  class="comment-input"
                                  rows="3"
                                  required
                                  placeholder="Ctrl+Entrée pour envoyer..."></textarea>
                        <button type="submit" class="submit-comment-btn">↩️ Répondre</button>
                        <button type="button" class="cancel-reply-btn"
                                onclick="cancelReply('reply-form-{{ $comment->id }}')">Annuler</button>
                    </form>
                </div>

                @if($comment->replies->count() > 0)
                    <div class="replies">
                        @foreach($comment->replies as $reply)
                            <div class="reply" id="comment-{{ $reply->id }}">
                                <img src="{{ $reply->user->profile_picture_url }}"
                                     alt="{{ $reply->user->name }}"
                                     style="width:32px;height:32px;min-width:32px;border-radius:50%;object-fit:cover;">

                                <div class="comment-body">
                                    <div class="comment-header">
                                        <span class="comment-author">{{ $reply->user->name }}</span>
                                        <span class="comment-role {{ $reply->user->isTeacher() ? 'teacher' : '' }}">
                                            {{ $reply->user->isTeacher() ? 'Enseignant' : 'Étudiant' }}
                                        </span>
                                        <span class="comment-time">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="comment-content">{{ $reply->content }}</div>

                                    <div class="comment-actions">
                                        {{-- Reply to a reply — prefills @replyAuthor, targets top-level form --}}
                                        <button class="reply-btn"
                                                onclick="toggleReply('reply-form-{{ $comment->id }}', '{{ addslashes($reply->user->name) }}'); document.getElementById('reply-form-{{ $comment->id }}').scrollIntoView({behavior:'smooth', block:'center'});">
                                            ↩️ Répondre
                                        </button>
                                        @if($reply->user_id === Auth::id())
                                            <form method="POST" action="{{ route('comments.destroy', $reply->id) }}" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="delete-btn"
                                                        onclick="return confirm('Supprimer ce commentaire?')">
                                                    🗑️ Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p style="color: #64748b; text-align: center;">Aucun commentaire</p>
    @endforelse

    <div class="add-comment-form">
        <form method="POST" action="{{ route('posts.comments.store', $post->id) }}">
            @csrf
            <textarea name="content"
                      class="comment-input"
                      rows="3"
                      required
                      placeholder="Écrivez un commentaire... (Ctrl+Entrée pour envoyer)"></textarea>
            <button type="submit" class="submit-comment-btn">💬 Commenter</button>
        </form>
    </div>
</div>

@endsection

@section('extra-scripts')
<script>
function toggleReply(id, authorName) {
    const form     = document.getElementById(id);
    const textarea = form.querySelector('textarea');
    const isOpening = !form.classList.contains('active');

    form.classList.toggle('active');

    if (isOpening) {
        // Prefill @mention only if textarea is empty
        if (authorName && textarea.value.trim() === '') {
            textarea.value = '@' + authorName + ' ';
        }
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
    }
}

function cancelReply(id) {
    const form     = document.getElementById(id);
    const textarea = form.querySelector('textarea');
    textarea.value = '';
    form.classList.remove('active');
}

function togglePostMenu() {
    const menu = document.getElementById('post-menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.post-menu-btn') && !e.target.closest('#post-menu')) {
        const menu = document.getElementById('post-menu');
        if (menu) menu.style.display = 'none';
    }
});

function openEditModal() {
    document.getElementById('edit-modal').classList.add('active');
    document.getElementById('post-menu').style.display = 'none';
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.remove('active');
}

document.getElementById('edit-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});

// Ctrl+Enter to submit any comment/reply form
document.querySelectorAll('.comment-input').forEach(textarea => {
    textarea.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            this.closest('form').submit();
        }
    });
});

document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;

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

<script>
// Scroll to & highlight target comment (from session flash or URL hash)
(function () {
    const newCommentId = @json(session('new_comment_id'));
    const scrollTo     = @json(session('scroll_to'));

    const hash   = window.location.hash;
    const hashId = hash ? hash.replace('#', '') : null;

    const targetId = hashId || (newCommentId ? 'comment-' + newCommentId : scrollTo);

    if (targetId) {
        const el = document.getElementById(targetId);
        if (el) {
            setTimeout(() => {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                const target = el.querySelector('.comment-body') || el;
                target.classList.add('comment-highlight');
            }, 100);
        }
    }
})();
</script>
@endsection