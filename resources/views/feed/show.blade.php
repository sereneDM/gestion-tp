@extends('layouts.app')
@section('title', 'Accueil')
@section('page-title', 'Fil d\'actualité')
@section('extra-styles')
<style>
.post-title {
    @apply text-3xl font-bold text-slate-900 dark:text-white;
}
.post-content {
    @apply text-base text-slate-700 dark:text-slate-300;
}
.replies {
    @apply mt-4 ml-4 pl-4 border-l-4 border-slate-300 dark:border-slate-600;
}
.reply {
    @apply flex gap-3 mb-4;
}
.reply-form { @apply hidden mt-3; }
.reply-form.active { @apply block; }
.add-comment-form {
    @apply mt-6 pt-4 border-t border-slate-300 dark:border-slate-600;
}
.comment-input {
    @apply w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-400 resize-vertical font-inherit text-sm;
}
.comment-input::placeholder { @apply text-slate-500 dark:text-slate-400; }
.submit-comment-btn {
    @apply mt-2 px-4 py-2 bg-violet-600 dark:bg-violet-600 hover:bg-violet-700 dark:hover:bg-violet-700 text-white rounded-lg cursor-pointer text-sm font-medium transition-colors duration-200;
}
.cancel-reply-btn {
    @apply ml-2 px-3 py-2 bg-slate-300 dark:bg-slate-600 hover:bg-slate-400 dark:hover:bg-slate-500 text-slate-900 dark:text-slate-100 rounded-lg cursor-pointer text-sm transition-colors duration-200;
}
.post-menu-btn {
    @apply bg-transparent border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-400 w-8 h-8 rounded-lg cursor-pointer text-lg flex items-center justify-center transition-all duration-150;
}
.post-menu-btn:hover {
    @apply bg-slate-200 dark:bg-slate-700 border-slate-400 dark:border-slate-500 text-slate-900 dark:text-slate-200;
}
.post-menu-dropdown {
    @apply hidden absolute top-10 right-0 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg min-w-[150px] z-100 shadow-lg;
}
.post-menu-dropdown button {
    @apply w-full text-left px-4 py-2 bg-transparent border-none text-red-600 dark:text-red-400 cursor-pointer rounded-lg text-sm transition-colors duration-150;
}
.post-menu-dropdown button:hover {
    @apply bg-red-50 dark:bg-slate-700;
}
.post-card {
    @apply relative;
}
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
                <option value="announcement">📢 Annonce importante</option>
                <option value="reminder">⏰ Rappel</option>
                <option value="general">📌 Publication générale</option>
            </select>
            @error('type')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Cours *</label>
            <div class="courses-checkboxes">
                {{-- "All students" option --}}
                <label class="course-checkbox-item all-option">
                    <input type="checkbox" name="class_ids[]" value="" id="course-all">
                    <span>🌍 Tous mes étudiants (publication générale)</span>
                </label>
                @foreach($courses as $course)
                    <label class="course-checkbox-item">
                        <input type="checkbox" name="class_ids[]" value="{{ $course->id }}"
                               class="course-specific"
                               {{ is_array(old('class_ids')) && in_array($course->id, old('class_ids')) ? 'checked' : '' }}>
                        <span>📚 {{ $course->name }} ({{ $course->students->count() }} étudiants)</span>
                    </label>
                @endforeach
            </div>
            <div class="selected-courses-hint" id="courses-hint">Aucun cours sélectionné — publication générale par défaut</div>
            @error('class_ids')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="title">Titre *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   placeholder="Ex: Rappel - TP à rendre vendredi"
                   maxlength="50" required>
            <div class="char-counter" id="title-counter">0 / 50</div>
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
const titleInput   = document.getElementById('title');
const titleCounter = document.getElementById('title-counter');
function updateTitleCounter() {
    const len = titleInput.value.length;
    titleCounter.textContent = len + ' / 50';
    titleCounter.classList.remove('warning', 'danger');
    if (len >= 50)       titleCounter.classList.add('danger');
    else if (len >= 40)  titleCounter.classList.add('warning');
}
titleInput.addEventListener('input', updateTitleCounter);
updateTitleCounter();
const allCheckbox      = document.getElementById('course-all');
const specificBoxes    = document.querySelectorAll('.course-specific');
const hint             = document.getElementById('courses-hint');
function updateHint() {
    const checked = [...specificBoxes].filter(cb => cb.checked);
    if (allCheckbox.checked || checked.length === 0) {
        hint.textContent = 'Publication générale — visible par tous vos étudiants';
    } else if (checked.length === 1) {
        hint.textContent = '1 cours sélectionné';
    } else {
        hint.textContent = checked.length + ' cours sélectionnés';
    }
}
allCheckbox.addEventListener('change', () => {
    if (allCheckbox.checked) {
        specificBoxes.forEach(cb => cb.checked = false);
    }
    updateHint();
});
specificBoxes.forEach(cb => {
    cb.addEventListener('change', () => {
        if (cb.checked) allCheckbox.checked = false;
        updateHint();
    });
});
updateHint();
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