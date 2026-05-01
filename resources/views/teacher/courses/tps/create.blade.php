@extends('layouts.app')

@section('title', 'Créer un TP')
@section('page-title', 'Créer un TP pour ' . $course->name)

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
                       maxlength="50"
                       required>
                <div class="char-counter" id="title-counter">0 / 50</div>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Description / Énoncé *</label>
                <div class="enonce-box">
                    <textarea required id="description"
                              name="description"
                              placeholder="Décrivez le TP et les objectifs d'apprentissage...">{{ old('description') }}</textarea>

                    <div class="pdf-section">
                        <span class="pdf-section-label">📎 Fichier PDF joint à l'énoncé</span>
                        <x-file-upload id="attachment" name="attachment" accept=".pdf" hint="PDF uniquement · max 10 Mo" />
                        @error('attachment') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="due_date">Date d'échéance</label>
                <input type="datetime-local"
                       id="due_date"
                       name="due_date"
                       value="{{ old('due_date', '') }}">
                <div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">
                    Par défaut: minuit (00:00) si l'heure n'est pas modifiée
                </div>
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

            <div class="form-group" id="post-option" style="display:none;">
                <div style="background: #0f172a; border-left: 4px solid #4f46e5; padding: 1rem; border-radius: 4px;">
                    <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer; font-weight:normal;">
                        <input type="checkbox" name="create_post" value="1" style="width:18px;height:18px;">
                        <div>
                            <div style="font-weight:bold; color: #e2e8f0;">📢 Publier une annonce dans le fil d'actualité</div>
                            <div style="font-size:0.85rem; color:#64748b; margin-top:0.25rem;">
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
        // Character counter for title
        const titleInput   = document.getElementById('title');
        const titleCounter = document.getElementById('title-counter');
        const maxLength    = 50;

        function updateCounter() {
            const len = titleInput.value.length;
            titleCounter.textContent = len + ' / ' + maxLength;
            titleCounter.classList.remove('warning', 'danger');
            if (len >= maxLength)        titleCounter.classList.add('danger');
            else if (len >= maxLength * 0.8) titleCounter.classList.add('warning');
        }
        titleInput.addEventListener('input', updateCounter);
        updateCounter(); // init on load (handles old() value)

        // Show/hide post option based on status
        document.getElementById('status').addEventListener('change', function () {
            document.getElementById('post-option').style.display =
                this.value === 'published' ? 'block' : 'none';
        });
        document.getElementById('status').dispatchEvent(new Event('change'));

        // Enforce min date
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