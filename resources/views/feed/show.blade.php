@extends('layouts.app')
@section('title', Str::limit($post->title, 50))
@section('page-title', 'Publication')
@section('extra-styles')
<style>
.post-content { color: var(--tp-text-secondary); line-height: 1.7; margin: 1.5rem 0; white-space: pre-line; }
.replies { margin-top: 1rem; margin-left: 1rem; padding-left: 1rem; border-left: 4px solid var(--tp-border); }
.reply { display: flex; gap: 0.75rem; margin-bottom: 1rem; }
.reply-form { display: none; margin-top: 0.75rem; }
.reply-form.active { display: block; }
.add-comment-form { margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--tp-border); }
.comment-input {
    width: 100%; padding: 0.6rem 0.75rem;
    border: 1px solid var(--tp-input-border); border-radius: 0.5rem;
    background: var(--tp-input-bg); color: var(--tp-text-primary);
    font-size: 0.9rem; resize: vertical; font-family: inherit; min-height: 80px; box-sizing: border-box;
}
.comment-input:focus { outline: none; border-color: var(--tp-accent); }
.submit-comment-btn {
    margin-top: 0.5rem; padding: 0.5rem 1rem; background: var(--tp-accent);
    color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: background 0.2s;
}
.submit-comment-btn:hover { background: var(--tp-accent-hover); }
.cancel-reply-btn {
    margin-left: 0.5rem; padding: 0.5rem 0.75rem; background: var(--tp-table-header);
    color: var(--tp-text-secondary); border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.875rem;
}
.post-menu-btn {
    background: transparent; border: 1px solid var(--tp-border); color: var(--tp-text-muted);
    width: 2rem; height: 2rem; border-radius: 0.5rem; cursor: pointer; font-size: 1.1rem;
    display: flex; align-items: center; justify-content: center; transition: all 0.15s;
}
.post-menu-btn:hover { background: var(--tp-hover-bg); color: var(--tp-text-primary); }
.post-menu-dropdown {
    display: none; position: absolute; top: 2.5rem; right: 0;
    background: var(--tp-bg-surface); border: 1px solid var(--tp-border);
    border-radius: 0.5rem; min-width: 150px; z-index: 100; box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
.post-menu-dropdown button {
    width: 100%; text-align: left; padding: 0.6rem 1rem; background: transparent;
    border: none; color: #ef4444; cursor: pointer; border-radius: 0.5rem; font-size: 0.875rem; transition: background 0.15s;
}
.post-menu-dropdown button:hover { background: var(--tp-hover-bg); }
.post-actions { display: flex; align-items: center; gap: 1rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--tp-border); }
.like-btn { background: none; border: none; padding: 0.25rem 0.4rem; cursor: pointer; color: var(--tp-text-muted); display: inline-flex; align-items: center; gap: 5px; font-size: 0.85rem; transition: color 0.15s; }
.like-btn:hover { color: #ef4444; }
.like-btn.liked { color: #ef4444; }
.like-icon { transition: transform 0.15s; display: inline-block; }
.like-btn:hover .like-icon, .like-btn.liked .like-icon { transform: scale(1.2); }
</style>
@endsection
@section('content')
<div style="position:relative; background: var(--tp-bg-raised); border: 1px solid var(--tp-border); border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px solid var(--tp-border);">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <img src="{{ $post->user->profile_picture_url }}" alt="{{ $post->user->name }}"
                 style="width:2.75rem; height:2.75rem; border-radius:50%; object-fit:cover;">
            <div>
                <div style="font-weight:bold; color:var(--tp-text-primary);">{{ $post->user->name }}</div>
                <div style="font-size:0.85rem; color:var(--tp-text-muted);">{{ $post->created_at->diffForHumans() }}</div>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:0.5rem;">
            <span style="padding:0.3rem 0.8rem; border-radius:9999px; font-size:0.8rem; font-weight:bold;
                background:{{ $post->type==='announcement'?'rgba(251,191,36,0.15)':($post->type==='reminder'?'rgba(239,68,68,0.15)':'rgba(99,102,241,0.15)') }};
                color:{{ $post->type==='announcement'?'#92400e':($post->type==='reminder'?'#991b1b':'#4f46e5') }};">
                @if($post->type==='announcement') 📢 Annonce
                @elseif($post->type==='reminder') ⏰ Rappel
                @else 📌 Général @endif
            </span>
            @if(Auth::id() === $post->user_id)
                <div style="position:relative;">
                    <button class="post-menu-btn" onclick="togglePostMenu()">⋮</button>
                    <div class="post-menu-dropdown" id="post-menu">
                        <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit">🗑️ Supprimer</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div style="font-size:1.3rem; font-weight:bold; color:var(--tp-text-primary); margin-bottom:0.5rem;">{{ $post->title }}</div>
    <div class="post-content">{{ $post->content }}</div>
    @if($post->class)
        <div style="margin-top:0.75rem; display:inline-block; padding:0.4rem 0.9rem; background:rgba(99,102,241,0.1); border-left:3px solid var(--tp-accent); border-radius:0.5rem; font-size:0.875rem; color:var(--tp-accent-text);">
            📚 {{ $post->class->name }}
        </div>
    @endif
    @if($post->attachment)
        <div style="margin-top:0.75rem;">
            <a href="{{ asset('storage/' . $post->attachment) }}" target="_blank"
               style="display:inline-block; padding:0.5rem 1rem; background:var(--tp-accent); color:white; border-radius:0.5rem; text-decoration:none; font-size:0.875rem;">
                📎 Télécharger la pièce jointe
            </a>
        </div>
    @endif
    <div class="post-actions">
        <button class="like-btn {{ $post->isLikedBy(auth()->id()) ? 'liked' : '' }}" data-id="{{ $post->id }}">
            <span class="like-icon">{{ $post->isLikedBy(auth()->id()) ? '❤️' : '🤍' }}</span>
            <span class="like-count">{{ $post->likes()->count() }}</span>
        </button>
        <span style="font-size:0.875rem; color:var(--tp-text-muted);">
            💬 {{ $post->comments->reduce(fn($c,$r)=>$c+1+$r->replies->count(),0) }} commentaire(s)
        </span>
    </div>
</div>

<div style="background: var(--tp-bg-raised); border: 1px solid var(--tp-border); border-radius: 1rem; padding: 1.5rem;" id="comments">
    <h3 style="font-size:1.1rem; font-weight:600; color:var(--tp-text-primary); margin-bottom:1.5rem;">
        💬 Commentaires ({{ $post->comments->reduce(fn($c,$r)=>$c+1+$r->replies->count(),0) }})
    </h3>
    @forelse($post->comments as $comment)
        <div style="display:flex; gap:0.75rem; margin-bottom:1.5rem;" id="comment-{{ $comment->id }}">
            <img src="{{ $comment->user->profile_picture_url }}" alt="{{ $comment->user->name }}"
                 style="width:2.25rem; height:2.25rem; border-radius:50%; object-fit:cover; flex-shrink:0;">
            <div style="flex:1;">
                <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
                    <span style="font-weight:bold; font-size:0.9rem; color:var(--tp-text-primary);">{{ $comment->user->name }}</span>
                    @if($comment->user->isTeacher())
                        <span style="font-size:0.75rem; padding:0.15rem 0.5rem; border-radius:9999px; background:rgba(236,72,153,0.15); color:#be185d;">Enseignant</span>
                    @endif
                    <span style="font-size:0.78rem; color:var(--tp-text-faint); margin-left:auto;">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div style="font-size:0.9rem; color:var(--tp-text-secondary); margin-bottom:0.5rem;">{{ $comment->content }}</div>
                <div style="display:flex; gap:0.75rem; font-size:0.8rem;">
                    <button style="background:none; border:none; color:var(--tp-accent-text); cursor:pointer; padding:0;" onclick="toggleReplyForm('reply-{{ $comment->id }}')">↩️ Répondre</button>
                    @if(Auth::id() === $comment->user_id)
                        <form method="POST" action="{{ route('posts.comments.destroy', $comment->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer; padding:0; font-size:0.8rem;">🗑️ Supprimer</button>
                        </form>
                    @endif
                </div>
                <div class="reply-form" id="reply-{{ $comment->id }}">
                    <form method="POST" action="{{ route('posts.comments.store', $post->id) }}" style="margin-top:0.75rem;">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="content" class="comment-input" placeholder="Votre réponse..." rows="2" required></textarea>
                        <div>
                            <button type="submit" class="submit-comment-btn">↩️ Répondre</button>
                            <button type="button" class="cancel-reply-btn" onclick="toggleReplyForm('reply-{{ $comment->id }}')">Annuler</button>
                        </div>
                    </form>
                </div>
                @if($comment->replies->count() > 0)
                    <div class="replies">
                        @foreach($comment->replies as $reply)
                            <div class="reply" id="comment-{{ $reply->id }}">
                                <img src="{{ $reply->user->profile_picture_url }}" alt="{{ $reply->user->name }}"
                                     style="width:2rem; height:2rem; border-radius:50%; object-fit:cover; flex-shrink:0;">
                                <div style="flex:1;">
                                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
                                        <span style="font-weight:bold; font-size:0.85rem; color:var(--tp-text-primary);">{{ $reply->user->name }}</span>
                                        <span style="font-size:0.78rem; color:var(--tp-text-faint); margin-left:auto;">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div style="font-size:0.875rem; color:var(--tp-text-secondary);">{{ $reply->content }}</div>
                                    @if(Auth::id() === $reply->user_id)
                                        <form method="POST" action="{{ route('posts.comments.destroy', $reply->id) }}" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:0.78rem; margin-top:0.25rem;">🗑️ Supprimer</button>
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
        <p style="text-align:center; color:var(--tp-text-muted); padding:2rem 0;">Aucun commentaire pour l'instant.</p>
    @endforelse

    <div class="add-comment-form">
        <h4 style="font-size:0.9rem; font-weight:600; color:var(--tp-text-secondary); margin-bottom:0.75rem;">Ajouter un commentaire</h4>
        <form method="POST" action="{{ route('posts.comments.store', $post->id) }}">
            @csrf
            <textarea name="content" class="comment-input" placeholder="Rédigez votre commentaire..." rows="3" required></textarea>
            @error('content')<div style="color:#ef4444; font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</div>@enderror
            <button type="submit" class="submit-comment-btn">💬 Commenter</button>
        </form>
    </div>
</div>
@endsection
@section('extra-scripts')
<script>
function togglePostMenu() {
    const menu = document.getElementById('post-menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.post-menu-btn') && !e.target.closest('.post-menu-dropdown')) {
        const menu = document.getElementById('post-menu');
        if (menu) menu.style.display = 'none';
    }
});
function toggleReplyForm(id) {
    document.getElementById(id).classList.toggle('active');
}
document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const res = await fetch(`/posts/${id}/like`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        });
        const data = await res.json();
        btn.querySelector('.like-icon').textContent = data.liked ? '❤️' : '🤍';
        btn.querySelector('.like-count').textContent = data.count;
        btn.classList.toggle('liked', data.liked);
    });
});
const params = new URLSearchParams(window.location.search);
const highlight = params.get('highlight');
if (highlight) {
    const el = document.getElementById('comment-' + highlight);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        el.style.transition = 'background 0.5s';
        el.style.background = 'rgba(124,58,237,0.1)';
        setTimeout(() => el.style.background = '', 2500);
    }
}
</script>
@endsection
