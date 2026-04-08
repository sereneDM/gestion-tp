@extends('layouts.teacher')

@section('title', 'Accueil')
@section('page-title', 'Fil d\'actualité')

@section('extra-styles')
<style>
    /* ✅ YOUR CSS — UNTOUCHED */
    .create-post-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .create-post-card h2 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: #333; font-weight: bold; }
    input[type="text"], textarea, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    textarea { min-height: 120px; resize: vertical; }
    input:focus, textarea:focus, select:focus { outline: none; border-color: #007bff; }
    .checkbox-group { display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem; }
    .checkbox-group input[type="checkbox"] { width: auto; }
    .btn-post {
        background: #28a745;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
    }
    .btn-post:hover { background: #218838; }

    .file-upload-zone {
        border: 2px dashed #007bff;
        padding: 1.5rem;
        text-align: center;
        border-radius: 4px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
    }
    .file-upload-zone:hover { background: #e7f3ff; }
    .file-upload-zone input[type="file"] { display: none; }

    .selected-file {
        margin-top: 1rem;
        padding: 0.5rem;
        background: #d4edda;
        border-left: 4px solid #28a745;
        border-radius: 4px;
    }

    .feed-section { margin-top: 2rem; }

    .post-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .post-info { flex: 1; }

    .post-type-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        margin-right: 0.5rem;
    }

    .type-announcement { background: #fff3cd; color: #856404; }
    .type-tp_posted { background: #d4edda; color: #155724; }
    .type-reminder { background: #f8d7da; color: #721c24; }
    .type-general { background: #d1ecf1; color: #0c5460; }

    .post-title { font-size: 1.3rem; font-weight: bold; color: #333; margin: 0.5rem 0; }
    .post-meta { font-size: 0.85rem; color: #666; margin-top: 0.5rem; }
    .post-content { color: #666; line-height: 1.6; margin: 1.5rem 0; }

    .post-course {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #e7f3ff;
        border-left: 4px solid #007bff;
        border-radius: 4px;
        margin-top: 1rem;
        font-size: 0.9rem;
    }

    .post-attachment { margin-top: 1rem; }

    .attachment-btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .attachment-btn:hover { background: #0056b3; }

    .btn-delete {
        background: #dc3545;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
    }
    .btn-delete:hover { background: #c82333; }

    .no-posts {
        background: white;
        padding: 3rem;
        text-align: center;
        color: #999;
        border-radius: 8px;
    }

    .error { color: #dc3545; font-size: 0.875rem; margin-top: 0.5rem; }
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

                <div class="file-upload-zone" onclick="document.getElementById('attachment').click()">

                    <input type="file" 

                           id="attachment" 

                           name="attachment" 

                           accept=".pdf,.jpg,.jpeg,.png,.zip"

                           onchange="showFileName(this)">

                    <div>

                        📎 Cliquez pour joindre un fichier

                    </div>

                    <div style="margin-top: 0.5rem; color: #666; font-size: 0.9rem;">

                        Formats: PDF, JPG, PNG, ZIP (Max: 10 Mo)

                    </div>

                </div>

                <div id="file-selected" class="selected-file" style="display: none;"></div>

                @error('attachment')

                    <div class="error">{{ $message }}</div>

                @enderror

            </div>



            <button type="submit" class="btn-post">

                📤 Publier

            </button>
    </form>
</div>

<!-- ✅ FEED -->
<div class="feed-section">
    <h2 style="margin-bottom: 1.5rem; color: #333;">📰 Mes publications</h2>

    @forelse($posts as $post)

    <!-- ✅ FIXED CARD -->
    <div class="post-card" onclick="window.location='{{ route('posts.show', $post->id) }}'" style="cursor:pointer;">
        
        <div class="post-header">
            <div style="display:flex; gap:1rem; align-items:flex-start;">
                
                <img src="{{ $post->user->profile_picture_url }}"
                     alt="{{ $post->user->name }}"
                     style="width:50px;height:50px;border-radius:50%;object-fit:cover;">

                <div class="post-info">
                    <div>
                        <span class="post-type-badge type-{{ $post->type }}">
                            @if($post->type === 'announcement') 📢 Annonce
                            @elseif($post->type === 'tp_posted') 📝 TP
                            @elseif($post->type === 'reminder') ⏰ Rappel
                            @else 📌 Général
                            @endif
                        </span>
                    </div>

                    <div class="post-title">{{ $post->title }}</div>

                    <div class="post-meta">
                        Publié {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>

                <form method="POST" action="{{ route('posts.destroy', $post->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" onclick="event.stopPropagation(); return confirm('Supprimer cette publication?')">
                        🗑️ Supprimer
                    </button>
                </form>

            </div>
        </div>

        <!-- ✅ MOVED INSIDE CARD (THIS WAS YOUR MAIN BUG) -->
        <div class="post-content">{{ $post->content }}</div>

        @if($post->class)
            <div class="post-course">
                📚 {{ $post->class->name }} ({{ $post->class->students->count() }} étudiants)
            </div>
        @else
            <div class="post-course">
                🌍 Publication générale
            </div>
        @endif

        @if($post->tp)
            <div style="margin-top: 1rem;">
                <a href="{{ route('teacher.tps.show', $post->tp->id) }}" class="attachment-btn">
                    👁️ Voir le TP
                </a>
            </div>
        @endif

        @if($post->attachment)
            <div class="post-attachment">
                <a href="{{ asset('storage/' . $post->attachment) }}" target="_blank" class="attachment-btn">
                    📎 Télécharger la pièce jointe
                </a>
            </div>
        @endif

    </div> <!-- ✅ CORRECT CLOSE -->

    @empty
        <div class="no-posts">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
            <h3>Aucune publication</h3>
            <p>Créez votre première publication pour communiquer avec vos étudiants</p>
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