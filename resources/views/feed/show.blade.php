@extends('layouts.app')

@section('title', $post->title)
@section('page-title', 'Publication')

@section('breadcrumbs')
    {{ Breadcrumbs::render('posts.show', $post) }}
@endsection

@section('extra-styles')
<style>
/* Page-specific styles */
.post-title { font-size: 1.8rem; }
.post-content { font-size: 1.05rem; }

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
}
.post-menu-dropdown button {
    width: 100%;
    text-align: left;
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    color: #fca5a5;
    cursor: pointer;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    transition: background 0.15s;
}
.post-menu-dropdown button:hover {
    background: #334155;
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
                <form method="POST" action="{{ route('posts.destroy', $post->id) }}"
                      style="display:block; width:100%;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">🗑️ Supprimer</button>
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
</div>

<div class="comments-section">
    <h3 style="margin-bottom: 1.5rem; color: #f1f5f9;">💬 Commentaires ({{ $post->comments->count() }})</h3>

    @forelse($post->comments as $comment)
        <div class="comment">
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
                    <button class="reply-btn" onclick="toggleReply('reply-form-{{ $comment->id }}')">
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
                                onclick="toggleReply('reply-form-{{ $comment->id }}')">Annuler</button>
                    </form>
                </div>

                @if($comment->replies->count() > 0)
                    <div class="replies">
                        @foreach($comment->replies as $reply)
                            <div class="reply">
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
                                    @if($reply->user_id === Auth::id())
                                        <form method="POST" action="{{ route('comments.destroy', $reply->id) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="delete-btn">🗑️ Supprimer</button>
                                        </form>
                                    @endif
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
                      required
                      placeholder="Écrivez un commentaire... (Ctrl+Entrée pour envoyer)"></textarea>
            <button type="submit" class="submit-comment-btn">💬 Commenter</button>
        </form>
    </div>
</div>

@section('extra-scripts')
<script>
function toggleReply(id) {
    document.getElementById(id).classList.toggle('active');
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
</script>
@endsection

@endsection