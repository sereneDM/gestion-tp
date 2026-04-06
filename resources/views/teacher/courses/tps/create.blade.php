@extends('layouts.teacher')

@section('title', 'Créer un TP')
@section('page-title', 'Créer un TP pour ' . $course->name)

@section('extra-styles')
<style>
    .form-container {
        max-width: 800px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: bold;
    }
    input, textarea, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    textarea {
        min-height: 150px;
        resize: vertical;
    }
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #007bff;
    }
    .enonce-box {
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }
    .enonce-box textarea {
        border: none;
        border-bottom: 1px solid #eee;
        border-radius: 0;
        margin: 0;
    }
    .enonce-box textarea:focus {
        outline: none;
        border-color: #eee;
        box-shadow: none;
    }
    .pdf-section {
        padding: 1rem;
        background: #fafafa;
    }
    .pdf-section-label {
        font-size: 0.85rem;
        font-weight: bold;
        color: #555;
        margin-bottom: 0.75rem;
        display: block;
    }
    .file-upload {
        border: 2px dashed #ccc;
        padding: 1rem;
        text-align: center;
        border-radius: 4px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.9rem;
        color: #666;
    }
    .file-upload:hover {
        background: #e7f3ff;
        border-color: #007bff;
        color: #007bff;
    }
    .file-upload input[type="file"] {
        display: none;
    }
    .selected-file {
        margin-top: 0.5rem;
        padding: 0.4rem 0.75rem;
        background: #d4edda;
        border-left: 3px solid #28a745;
        border-radius: 4px;
        font-size: 0.85rem;
    }
    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        display: inline-block;
        flex: 1;
        text-align: center;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-primary:hover { background-color: #0056b3; }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover { background-color: #545b62; }
    .course-info {
        background: #e7f3ff;
        border-left: 4px solid #007bff;
        padding: 1rem;
        margin-bottom: 2rem;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
    <div class="form-container">
        <div class="course-info">
            📚 <strong>Cours:</strong> {{ $course->name }}
        </div>

        <form method="POST" action="{{ route('teacher.courses.tps.store', $course->id) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Titre du TP *</label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title') }}"
                       placeholder="Ex: TP1 - Introduction au Machine Learning"
                       required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Description / Énoncé *</label>
                <div class="enonce-box">
                    <textarea id="description"
                              name="description"
                              required
                              placeholder="Décrivez le TP et les objectifs d'apprentissage...">{{ old('description') }}</textarea>

                    <div class="pdf-section">
                        <span class="pdf-section-label">📎 Fichier PDF joint à l'énoncé (optionnel)</span>
                        <div class="file-upload" onclick="document.getElementById('attachment').click()">
                            <input type="file"
                                   id="attachment"
                                   name="attachment"
                                   accept=".pdf"
                                   onchange="showFileName(this)">
                            📎 Cliquez pour sélectionner un fichier PDF
                            <div style="font-size: 0.8rem; margin-top: 0.25rem; color: #999;">
                                PDF uniquement · max 10 Mo
                            </div>
                        </div>
                        <div id="file-selected" class="selected-file" style="display: none;"></div>
                        @error('attachment') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="due_date">Date d'échéance</label>
                <input type="date"
                       id="due_date"
                       name="due_date"
                       value="{{ old('due_date') }}">
                @error('due_date') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="status">Statut *</label>
                <select id="status" name="status" required>
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>
                        Publié (visible aux étudiants)
                    </option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>
                        Brouillon (non visible aux étudiants)
                    </option>
                </select>
                @error('status') <div class="error">{{ $message }}</div> @enderror
            </div>
            {{-- Add this block just before the button-group div --}}
<div class="form-group" id="post-option" style="display:none;">
    <div style="background: #e7f3ff; border-left: 4px solid #007bff; padding: 1rem; border-radius: 4px;">
        <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer; font-weight:normal;">
            <input type="checkbox" name="create_post" value="1" style="width:18px;height:18px;">
            <div>
                <div style="font-weight:bold;">📢 Publier une annonce dans le fil d'actualité</div>
                <div style="font-size:0.85rem; color:#555; margin-top:0.25rem;">
                    Les étudiants verront une notification dans leur fil d'actualité
                </div>
            </div>
        </label>
    </div>
</div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">✓ Créer le TP</button>
                <a href="{{ route('teacher.courses.show', $course->id) }}" class="btn btn-secondary">✗ Annuler</a>
            </div>
        </form>
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
    <script>
    document.getElementById('status').addEventListener('change', function() {
        const postOption = document.getElementById('post-option');
        postOption.style.display = this.value === 'published' ? 'block' : 'none';
    });
    // Run on load in case of old() value
    document.getElementById('status').dispatchEvent(new Event('change'));
</script>
@endsection