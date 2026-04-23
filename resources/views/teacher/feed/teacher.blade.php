@extends('layouts.app')

@section('title', 'Accueil')
@section('page-title', 'Fil d\'actualité')

@section('extra-styles')
<style>
    .create-post-card {
        background: #0f172a;
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid #334155;
        margin-bottom: 2rem;
    }
    .create-post-card h2 {
        margin-top: 0;
        color: #f1f5f9;
        border-bottom: 1px solid #334155;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
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

    /* Pagination Styles */
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
    .page-link:hover {
        background: #1e293b;
        color: #e2e8f0;
        border-color: #475569;
    }
    .page-item.active .page-link {
        background: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }
    .page-item.disabled .page-link {
        color: #64748b;
        background: #0f172a;
        border-color: #334155;
        cursor: not-allowed;
    }

    /* Breadcrumb Styles */
    .breadcrumb {
        background: transparent;
        margin-bottom: 1rem;
        padding: 0;
    }
    .breadcrumb-item {
        color: #94a3b8;
    }
    .breadcrumb-item a {
        color: #cbd5e1;
        text-decoration: none;
    }
    .breadcrumb-item a:hover {
        color: #e2e8f0;
    }
    .breadcrumb-item.active {
        color: #e2e8f0;
        font-weight: bold;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: #64748b;
        content: "/";
    }
</style>
@endsection

@section('content')

<!-- ✅ FORM (UNCHANGED) -->
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

                @error('type')

                    <div class="error">{{ $message }}</div>

                @enderror

            </div>



            <div class="form-group">

                <label for="class_id">Cours (optionnel - laissez vide pour publication générale)</label>

                <select id="class_id" name="class_id">

                    <option value="">Tous mes étudiants (publication générale)</option>

                    @foreach($courses as $course)

                        <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->students->count() }} étudiants)</option>

                    @endforeach

                </select>

                @error('class_id')

                    <div class="error">{{ $message }}</div>

                @enderror

            </div>



            <div class="form-group">

                <label for="title">Titre *</label>

                <input type="text" 

                       id="title" 

                       name="title" 

                       value="{{ old('title') }}"

                       placeholder="Ex: Rappel - TP à rendre vendredi"

                       required>

                @error('title')

                    <div class="error">{{ $message }}</div>

                @enderror

            </div>



            <div class="form-group">

                <label for="content">Contenu *</label>

                <textarea id="content" 

                          name="content" 

                          required

                          placeholder="Écrivez votre message...">{{ old('content') }}</textarea>

                @error('content')

                    <div class="error">{{ $message }}</div>

                @enderror

            </div>



            <div class="form-group">

                <label>Pièce jointe (optionnel)</label>

                <x-file-upload id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.zip" hint="PDF, JPG, PNG, ZIP · max 10 Mo" />

                @error('attachment')

                    <div class="error">{{ $message }}</div>

                @enderror

            </div>



            <button type="submit" class="btn-post">

                📤 Publier

            </button>
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