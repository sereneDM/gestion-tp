@extends(Auth::user()->isTeacher() ? 'layouts.teacher' : 'layouts.student')

@section('title', $post->title)
@section('page-title', 'Publication')

@section('extra-styles')
<style>
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
    .type-tp_posted    { background: #d4edda; color: #155724; }
    .type-reminder     { background: #f8d7da; color: #721c24; }
    .type-general      { background: #d1ecf1; color: #0c5460; }
    .post-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .post-meta {
        color: #999;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .post-content {
        color: #444;
        line-height: 1.8;
        font-size: 1.05rem;
        white-space: pre-wrap;
    }
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

    /* Comments */
    .comments-section {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .comments-section h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .comment {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .comment-avatar {
        width: 40px;
        height: 40px;
        min-width: 40px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1rem;
    }
    .comment-avatar.teacher-avatar {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }
    .comment-body {
        flex: 1;
    }
    .comment-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.4rem;
    }
    .comment-author {
        font-weight: bold;
        color: #333;
        font-size: 0.95rem;
    }
    .comment-role {
        font-size: 0.75rem;
        padding: 0.1rem 0.5rem;
        border-radius: 10px;
        background: #e9ecef;
        color: #666;
    }
    .comment-role.teacher { background: #fce4ec; color: #c2185b; }
    .comment-time {
        font-size: 0.8rem;
        color: #999;
        margin-left: auto;
    }
    .comment-content {
        color: #444;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }
    .comment-actions {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
    }
    .reply-btn {
        color: #007bff;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        font-size: 0.85rem;
    }
    .reply-btn:hover { text-decoration: underline; }
    .delete-btn {
        color: #dc3545;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        font-size: 0.85rem;
    }
    .delete-btn:hover { text-decoration: underline; }

    /* Replies */
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
    .reply-avatar {
        width: 32px;
        height: 32px;
        min-width: 32px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.8rem;
    }
    .reply-avatar.teacher-avatar {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    /* Reply form */
    .reply-form {
        margin-top: 0.75rem;
        display: none;
    }
    .reply-form.active { display: block; }

    /* Add comment form */
    .add-comment-form {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid #f0f0f0;
    }
    .add-comment-form h4 {
        margin-top: 0;
        color: #333;
        margin-bottom: 1rem;
    }
    .comment-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
        resize: vertical;
        min-height: 80px;
        font-family: inherit;
    }
    .comment-input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }
    .submit-comment-btn {
        margin-top: 0.75rem;
        padding: 0.6rem 1.5rem;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        cursor: pointer;
        font-weight: bold;
    }
    .submit-comment-btn:hover { background: #0056b3; }
    .cancel-reply-btn {
        margin-top: 0.75rem;
        margin-left: 0.5rem;
        padding: 0.6rem 1rem;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
    }
    .alert-success {
        background: #d4edda;
        border-left: 4px solid #28a745;
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        color: #155724;
    }
</style>
@endsection

@section('content')
    <a href="{{ url()->previous() }}" class="back-btn">← Retour</a>


    {{-- Post --}}
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

    {{-- Comments --}}
    <div class="comments-section">
        <h3>💬 Commentaires ({{ $post->comments->count() }})</h3>

        @forelse($post->comments as $comment)
            <div class="comment">
                <div class="comment-avatar {{ $comment->user->isTeacher() ? 'teacher-avatar' : '' }}">
                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                </div>
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

                    {{-- Reply form --}}
                    <div class="reply-form" id="reply-form-{{ $comment->id }}">
                        <form method="POST" action="{{ route('posts.comments.store', $post->id) }}">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <textarea name="content" class="comment-input" placeholder="Votre réponse..." rows="3" required></textarea>
                            <button type="submit" class="submit-comment-btn">↩️ Répondre</button>
                            <button type="button" class="cancel-reply-btn" onclick="toggleReply('reply-form-{{ $comment->id }}')">Annuler</button>
                        </form>
                    </div>

                    {{-- Replies --}}
                    @if($comment->replies->count() > 0)
                        <div class="replies">
                            @foreach($comment->replies as $reply)
                                <div class="reply">
                                    <div class="reply-avatar {{ $reply->user->isTeacher() ? 'teacher-avatar' : '' }}">
                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                    </div>
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
                                            <div class="comment-actions">
                                                <form method="POST" action="{{ route('comments.destroy', $reply->id) }}" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="delete-btn" onclick="return confirm('Supprimer?')">
                                                        🗑️ Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p style="color: #999; text-align: center; padding: 1rem;">Aucun commentaire pour l'instant. Soyez le premier!</p>
        @endforelse

        {{-- Add comment --}}
        <div class="add-comment-form">
            <h4>✍️ Ajouter un commentaire</h4>
            <form method="POST" action="{{ route('posts.comments.store', $post->id) }}">
                @csrf
                <textarea name="content" class="comment-input" placeholder="Écrivez votre commentaire..." required></textarea>
                <button type="submit" class="submit-comment-btn">💬 Commenter</button>
            </form>
        </div>
    </div>

    <script>
        function toggleReply(id) {
            const form = document.getElementById(id);
            form.classList.toggle('active');
        }
    </script>
@endsection