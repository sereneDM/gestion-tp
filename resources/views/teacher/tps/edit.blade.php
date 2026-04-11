@extends('layouts.teacher')

@section('title', 'Modifier le TP')
@section('page-title', 'Modifier: ' . $tp->title)

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
    .current-file {
        background: #e7f3ff;
        border-left: 3px solid #007bff;
        padding: 0.6rem 0.9rem;
        border-radius: 4px;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
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
            📚 <strong>Cours:</strong> {{ $tp->class->name }}
        </div>

        <form method="POST" action="{{ route('teacher.tps.update', $tp->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Titre du TP *</label>
                <input type="text"
                       id="title"
                       name="title"
                       value="{{ old('title', $tp->title) }}"
                       required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Description / Énoncé *</label>
                <div class="enonce-box">
                    <textarea id="description"
                              name="description"
                              >{{ old('description', $tp->description) }}</textarea>

                    <div class="pdf-section">
                        <span class="pdf-section-label">📎 Fichier PDF joint à l'énoncé (optionnel)</span>

                        @if($tp->attachments)
                            <div class="current-file">
                                📄 Fichier actuel:
                                <a href="{{ asset('storage/' . $tp->attachments) }}" target="_blank" style="color: #007bff;">
                                    Télécharger
                                </a>
                            </div>
                        @endif

                        <div class="file-upload" onclick="document.getElementById('attachment').click()">
                            <input type="file"
                                   id="attachment"
                                   name="attachment"
                                   accept=".pdf"
                                   onchange="showFileName(this)">
                            📎 Cliquez pour {{ $tp->attachments ? 'remplacer' : 'sélectionner' }} un fichier PDF
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
    <input type="datetime-local"
           id="due_date"
           name="due_date"
           value="{{ old('due_date', $tp->due_date ? $tp->due_date->format('Y-m-d\TH:i') : '') }}">
    <div style="font-size:0.8rem; color:#999; margin-top:0.25rem;">
        Par défaut: minuit (00:00) si l'heure n'est pas modifiée
    </div>
    @error('due_date') <div class="error">{{ $message }}</div> @enderror
</div>

            <div class="form-group">
                <label for="status">Statut *</label>
                <select id="status" name="status" required>
                    <option value="draft" {{ old('status', $tp->status) === 'draft' ? 'selected' : '' }}>
                        Brouillon (non visible aux étudiants)
                    </option>
                    <option value="published" {{ old('status', $tp->status) === 'published' ? 'selected' : '' }}>
                        Publié (visible aux étudiants)
                    </option>
                    <option value="closed" {{ old('status', $tp->status) === 'closed' ? 'selected' : '' }}>
                        Fermé (plus de soumissions acceptées)
                    </option>
                </select>
                @error('status') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">✓ Enregistrer les modifications</button>
                <a href="{{ route('teacher.courses.show', $tp->class_id) }}#tps" class="btn btn-secondary">✗ Annuler</a>
            </div>
        </form>
    </div>

    <script>
        function showFileName(input) {
            const fileSelected = document.getElementById('file-selected');
            if (input.files && input.files[0]) {
                fileSelected.style.display = 'block';
                fileSelected.innerHTML = '✓ Nouveau fichier: ' + input.files[0].name;
            } else {
                fileSelected.style.display = 'none';
            }
        }
    </script>
   <script>
    const dueDateInput = document.getElementById('due_date');
    const currentValue = dueDateInput.value;
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    const nowStr = now.toISOString().slice(0, 16);
    // Only enforce min if no existing past date is set
    if (!currentValue || currentValue >= nowStr) {
        dueDateInput.min = nowStr;
    }
</script>
@endsection
