@extends('layouts.app')
@section('title', 'Accueil')
@section('page-title', 'Fil d\'actualité')
@section('extra-styles')
<style>
    .create-post-card {
        background: var(--tp-bg-raised);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid var(--tp-border);
        margin-bottom: 2rem;
    }
    .create-post-card h2 {
        margin-top: 0;
        color: var(--tp-text-primary);
        border-bottom: 1px solid var(--tp-border);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: var(--tp-text-secondary); font-weight: bold; }
    input[type="text"], textarea, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--tp-input-border);
        border-radius: 6px;
        font-size: 1rem;
        background: var(--tp-input-bg);
        color: var(--tp-text-primary);
        box-sizing: border-box;
    }
    input[type="text"]::placeholder,
    textarea::placeholder { color: var(--tp-text-faint); }
    select option { background: var(--tp-input-bg); color: var(--tp-text-primary); }
    textarea { min-height: 120px; resize: vertical; }
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: var(--tp-accent);
        box-shadow: 0 0 0 2px rgba(124,58,237,0.15);
    }
    .char-counter {
        text-align: right;
        font-size: 0.8rem;
        color: var(--tp-text-faint);
        margin-top: 0.25rem;
    }
    .course-checkboxes {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--tp-input-bg);
        border: 1px solid var(--tp-input-border);
        border-radius: 6px;
    }
    .course-checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        border: 1px solid var(--tp-border);
        background: var(--tp-bg-surface);
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s;
        font-size: 0.875rem;
        color: var(--tp-text-secondary);
        user-select: none;
    }
    .course-checkbox-item:has(input:checked) {
        border-color: var(--tp-accent);
        background: rgba(124,58,237,0.08);
        color: var(--tp-text-primary);
    }
    .course-checkbox-item input[type="checkbox"] {
        width: auto;
        margin: 0;
        accent-color: var(--tp-accent);
        cursor: pointer;
    }
    .general-note {
        margin-top: 0.5rem;
        font-size: 0.82rem;
        color: var(--tp-text-faint);
        font-style: italic;
    }
    .btn-post {
        background: var(--tp-accent);
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-post:hover { background: var(--tp-accent-hover); }
    .error {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    [data-theme="dark"] .error { color: #fca5a5; }
    .breadcrumb { background: transparent; margin-bottom: 1rem; padding: 0; }
    .breadcrumb-item { color: var(--tp-text-muted); }
    .breadcrumb-item a { color: var(--tp-text-secondary); text-decoration: none; }
    .breadcrumb-item a:hover { color: var(--tp-text-primary); }
    .breadcrumb-item.active { color: var(--tp-text-primary); font-weight: bold; }
    .breadcrumb-item + .breadcrumb-item::before { color: var(--tp-text-faint); content: "/"; }
    .post-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px solid var(--tp-border);
    }
    .like-btn {
        background: none;
        border: none;
        padding: 0.25rem 0.4rem;
        cursor: pointer;
        color: var(--tp-text-muted);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        transition: color 0.15s;
    }
    .like-btn:hover { color: #e11d48; }
    .like-btn:hover .like-icon { transform: scale(1.3); }
    .like-btn.liked { color: #e11d48; }
    .like-btn.liked .like-icon { transform: scale(1.15); }
    .like-icon { transition: transform 0.15s; display: inline-block; }
    .comment-count-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        color: var(--tp-text-muted);
        text-decoration: none;
        transition: color 0.15s;
    }
    .comment-count-link:hover { color: var(--tp-text-primary); }
    .feed-section h2 { color: var(--tp-text-primary); }
</style>
@endsection
@section('content')
<div class="create-post-card">
    <h2>✍️ Créer une publication</h2>
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="type">Type de publication *</label>
            <select id="type" name="type" required>
                <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>📢 Annonce importante</option>
                <option value="reminder"     {{ old('type') === 'reminder'     ? 'selected' : '' }}>⏰ Rappel</option>
                <option value="general"      {{ old('type') === 'general'      ? 'selected' : '' }}>📌 Publication générale</option>
            </select>
            @error('type')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Cours *</label>
            <div class="course-checkboxes">
                <label class="course-checkbox-item">
                    <input type="checkbox" name="class_ids[]" value="all" id="course_all"
                           {{ !old('class_ids') ? 'checked' : '' }}>
                    🌍 Tous mes étudiants (publication générale)
                </label>
                @foreach($courses as $course)
                    <label class="course-checkbox-item">
                        <input type="checkbox" name="class_ids[]" value="{{ $course->id }}"
                               {{ in_array($course->id, old('class_ids', [])) ? 'checked' : '' }}>
                        📚 {{ $course->name }} ({{ $course->students->count() }} étudiants)
                    </label>
                @endforeach
            </div>
            <div class="general-note">Publication générale — visible par tous vos étudiants</div>
            @error('class_ids')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="title">Titre *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   placeholder="Ex: Rappel - TP à rendre vendredi"
                   maxlength="50" required
                   oninput="document.getElementById('title-count').textContent = this.value.length">
            <div class="char-counter"><span id="title-count">{{ strlen(old('title', '')) }}</span> / 50</div>
            @error('title')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="content">Contenu *</label>
            <textarea id="content" name="content" required
                      placeholder="Écrivez votre message...">{{ old('content') }}</textarea>
            @error('content')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Pièce jointe (optionnel)</label>
            <x-file-upload id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.zip" hint="PDF, JPG, PNG, ZIP · max 10 Mo" />
            @error('attachment')<div class="error">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn-post">📤 Publier</button>
    </form>
</div>

<div class="feed-section">
    <h2 style="margin-bottom: 1.5rem;">📰 Mes publications</h2>
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
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--tp-border); color: var(--tp-text-secondary); font-size: 0.9rem;">
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
            <h3 style="color: var(--tp-text-muted);">Aucune publication</h3>
            <p style="margin-top: 0.5rem; color: var(--tp-text-muted);">Créez votre première publication pour communiquer avec vos étudiants</p>
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
// Mutual exclusion: "Tous mes étudiants" unchecks others and vice versa
const allCheckbox = document.getElementById('course_all');
const courseCheckboxes = document.querySelectorAll('input[name="class_ids[]"]:not(#course_all)');

allCheckbox.addEventListener('change', () => {
    if (allCheckbox.checked) {
        courseCheckboxes.forEach(cb => cb.checked = false);
    }
});
courseCheckboxes.forEach(cb => {
    cb.addEventListener('change', () => {
        if (cb.checked) allCheckbox.checked = false;
    });
});

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