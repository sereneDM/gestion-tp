@extends(Auth::user()->isTeacher() ? 'layouts.teacher' : 'layouts.student')

@section('title', $post->title)
@section('page-title', 'Publication')

@section('extra-styles')
<style>
/* CSS UNCHANGED */
.post-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}
.post-type-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
    margin-bottom: 1rem;
    display: inline-block;
}
.type-announcement { background: #fff3cd; color: #856404; }
.type-tp_posted { background: #d4edda; color: #155724; }
.type-reminder { background: #f8d7da; color: #721c24; }
.type-general { background: #d1ecf1; color: #0c5460; }
.post-title { font-size: 1.8rem; font-weight: bold; color: #333; margin-bottom: 0.5rem; }
.post-meta { color: #999; font-size: 0.9rem; margin-bottom: 1.5rem; }
.post-content { color: #444; line-height: 1.8; font-size: 1.05rem; white-space: pre-wrap; }
.post-course {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #e7f3ff;
    border-left: 4px solid #007bff;
    border-radius: 4px;
    margin-top: 1.5rem;
    font-size: 0.9rem;
}
.attachment-btn {
    display: inline-block;
    margin-top: 1rem;
    padding: 0.6rem 1.2rem;
    background: #007bff;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9rem;
}
.back-btn {
    display: inline-block;
    margin-bottom: 1.5rem;
    color: #007bff;
    text-decoration: none;
    font-size: 0.95rem;
}
.back-btn:hover { text-decoration: underline; }

/* comments unchanged */
.comments-section {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.comment {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.comment-body { flex: 1; }
.comment-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.4rem;
}
.comment-author { font-weight: bold; color: #333; font-size: 0.95rem; }
.comment-role {
    font-size: 0.75rem;
    padding: 0.1rem 0.5rem;
    border-radius: 10px;
    background: #e9ecef;
    color: #666;
}
.comment-role.teacher { background: #fce4ec; color: #c2185b; }
.comment-time { font-size: 0.8rem; color: #999; margin-left: auto; }
.comment-content { color: #444; line-height: 1.6; margin-bottom: 0.5rem; }
.comment-actions { display: flex; gap: 1rem; font-size: 0.85rem; }
.reply-btn, .delete-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}
.reply-btn { color: #007bff; }
.delete-btn { color: #dc3545; }

.replies {
    margin-top: 1rem;
    margin-left: 1rem;
    padding-left: 1rem;
    border-left: 3px solid #e9ecef;
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
    border-top: 2px solid #f0f0f0;
}
.comment-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
}
.submit-comment-btn {
    margin-top: 0.75rem;
    padding: 0.6rem 1.5rem;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
}
.cancel-reply-btn {
    margin-left: 0.5rem;
    padding: 0.6rem 1rem;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
}
</style>
@endsection

@section('content')

@section('breadcrumbs')
    {{ Breadcrumbs::render('posts.show', $post) }}
@endsection

<div class="post-card">
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
    <div class="post-content">{{ $post->content }}</div>

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
    <h3 style="margin-bottom: 1.5rem;">💬 Commentaires ({{ $post->comments->count() }})</h3>

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
        <p style="color: #999; text-align: center;">Aucun commentaire</p>
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

<script>
function toggleReply(id) {
    document.getElementById(id).classList.toggle('active');
}
</script>

@endsection