@extends('layouts.app')

@section('title', 'Modifier le TP')
@section('page-title', 'Modifier: ' . Str::limit($tp->title, 50))

@section('extra-styles')
<style>
    .form-container {
        max-width: 800px;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 1rem;
        padding: 2rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #cbd5e1;
        font-weight: bold;
    }
    input, textarea, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #475569;
        border-radius: 0.75rem;
        font-size: 1rem;
        background: #1e293b;
        color: #e2e8f0;
        box-sizing: border-box;
    }
    textarea {
        min-height: 150px;
        resize: vertical;
    }
    input::placeholder, textarea::placeholder {
        color: #94a3b8;
    }
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: #6366f1;
    }
    .char-counter {
        text-align: right;
        font-size: 0.78rem;
        margin-top: 0.25rem;
        color: #64748b;
        transition: color 0.2s;
    }
    .char-counter.warning { color: #f59e0b; }
    .char-counter.danger  { color: #ef4444; }
    .enonce-box {
        border: 1px solid #475569;
        border-radius: 0.75rem;
        overflow: hidden;
        background: #0f172a;
    }
    .enonce-box textarea {
        border: none;
        border-bottom: 1px solid #334155;
        border-radius: 0;
        margin: 0;
        background: #0f172a;
        color: #e2e8f0;
    }
    .enonce-box textarea:focus {
        outline: none;
        border-color: #334155;
        box-shadow: none;
    }
    .pdf-section {
        padding: 1rem;
        background: #0f172a;
    }
    .pdf-section-label {
        font-size: 0.85rem;
        font-weight: bold;
        color: #cbd5e1;
        margin-bottom: 0.75rem;
        display: block;
    }
    .current-file {
        background: rgba(59,130,246,0.12);
        border-left: 3px solid #4f46e5;
        padding: 0.6rem 0.9rem;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        color: #cbd5e1;
    }
    .file-upload {
        border: 2px dashed #475569;
        padding: 1rem;
        text-align: center;
        border-radius: 0.75rem;
        background: #1e293b;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.9rem;
        color: #cbd5e1;
    }
    .file-upload:hover {
        background: #273548;
        border-color: #6366f1;
        color: #a5b4fc;
    }
    .file-upload input[type="file"] {
        display: none;
    }
    .selected-file {
        margin-top: 0.5rem;
        padding: 0.4rem 0.75rem;
        background: rgba(34,197,94,0.1);
        border-left: 3px solid #22c55e;
        border-radius: 0.75rem;
        font-size: 0.85rem;
        color: #a7f3d0;
    }
    .error {
        color: #fca5a5;
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
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        display: inline-block;
        flex: 1;
        text-align: center;
        color: #e2e8f0;
    }
    .btn-primary {
        background-color: #4f46e5;
        color: white;
    }
    .btn-primary:hover { background-color: #4338ca; }
    .btn-secondary {
        background-color: #475569;
        color: white;
    }
    .btn-secondary:hover { background-color: #334155; }
    .course-info {
        background: #0f172a;
        border-left: 4px solid #4f46e5;
        padding: 1rem;
        margin-bottom: 2rem;
        border-radius: 0.75rem;
        color: #cbd5e1;
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
                       maxlength="50"
                       required>
                <div class="char-counter" id="title-counter">0 / 50</div>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Description / Énoncé *</label>
                <div class="enonce-box">
                    <textarea id="description"
                              name="description">{{ old('description', $tp->description) }}</textarea>

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
                            <div style="font-size: 0.8rem; margin-top: 0.25rem; color: #64748b;">
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
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">
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
                <a href="{{ route('teacher.courses.show', $tp->class_id) }}?tab=tps" class="btn btn-secondary">✗ Annuler</a>
            </div>
        </form>
    </div>

    <script>
        // Character counter for title
        const titleInput   = document.getElementById('title');
        const titleCounter = document.getElementById('title-counter');
        const maxLength    = 50;

        function updateCounter() {
            const len = titleInput.value.length;
            titleCounter.textContent = len + ' / ' + maxLength;
            titleCounter.classList.remove('warning', 'danger');
            if (len >= maxLength)             titleCounter.classList.add('danger');
            else if (len >= maxLength * 0.8)  titleCounter.classList.add('warning');
        }
        titleInput.addEventListener('input', updateCounter);
        updateCounter(); // init with existing value

        // File name display
        function showFileName(input) {
            const fileSelected = document.getElementById('file-selected');
            if (input.files && input.files[0]) {
                fileSelected.style.display = 'block';
                fileSelected.innerHTML = '✓ Nouveau fichier: ' + input.files[0].name;
            } else {
                fileSelected.style.display = 'none';
            }
        }

        // Enforce min date (only if no past date already set)
        const dueDateInput = document.getElementById('due_date');
        const currentValue = dueDateInput.value;
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const nowStr = now.toISOString().slice(0, 16);
        if (!currentValue || currentValue >= nowStr) {
            dueDateInput.min = nowStr;
        }
    </script>
@endsection