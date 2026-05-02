@extends('layouts.app')

@section('title', 'Créer un TP')
@section('page-title', 'Créer un TP pour ' . $course->name)

@section('extra-styles')
<style>
    .form-container {
        max-width: 800px;
        background: var(--tp-bg-raised);
        border: 1px solid var(--tp-border);
        border-radius: 1rem;
        padding: 2rem;
    }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: var(--tp-text-secondary); font-weight: bold; }
    input, textarea, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--tp-input-border);
        border-radius: 0.75rem;
        font-size: 1rem;
        background: var(--tp-input-bg);
        color: var(--tp-text-primary);
        box-sizing: border-box;
    }
    textarea { min-height: 150px; resize: vertical; }
    input::placeholder, textarea::placeholder { color: var(--tp-text-faint); }
    input:focus, textarea:focus, select:focus { outline: none; border-color: #6366f1; }
    select option { background: var(--tp-input-bg); color: var(--tp-text-primary); }
    .error { color: #f87171; font-size: 0.875rem; margin-top: 0.25rem; }
    [data-theme="dark"] .error { color: #fca5a5; }

    .enonce-box {
        border: 1px solid var(--tp-input-border);
        border-radius: 0.75rem;
        overflow: hidden;
        background: var(--tp-bg-raised);
    }
    .enonce-box textarea {
        border: none;
        border-bottom: 1px solid var(--tp-border);
        border-radius: 0;
        margin: 0;
        background: var(--tp-bg-raised);
        color: var(--tp-text-primary);
    }
    .enonce-box textarea:focus { outline: none; border-color: var(--tp-border); box-shadow: none; }
    .pdf-section {
        padding: 1rem;
        background: var(--tp-bg-raised);
    }
    .pdf-section-label {
        font-size: 0.85rem;
        font-weight: bold;
        color: var(--tp-text-secondary);
        margin-bottom: 0.75rem;
        display: block;
    }
    .due-hint { font-size: 0.8rem; color: var(--tp-text-faint); margin-top: 0.25rem; }

    .button-group { display: flex; gap: 1rem; margin-top: 2rem; }
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
        transition: opacity 0.15s;
    }
    .btn:hover { opacity: 0.9; }
    .btn-primary  { background: var(--tp-accent); color: white; }
    .btn-primary:hover  { background: var(--tp-accent-hover); opacity: 1; }
    .btn-secondary { background: var(--tp-table-header); color: var(--tp-text-secondary); }

    .course-info {
        background: var(--tp-bg-raised);
        border-left: 4px solid var(--tp-accent);
        padding: 1rem;
        margin-bottom: 2rem;
        border-radius: 0.75rem;
        color: var(--tp-text-secondary);
    }

    .post-option-box {
        background: var(--tp-bg-raised);
        border-left: 4px solid var(--tp-accent);
        padding: 1rem;
        border-radius: 4px;
    }
    .post-option-box .opt-title { font-weight: bold; color: var(--tp-text-primary); }
    .post-option-box .opt-hint { font-size: 0.85rem; color: var(--tp-text-faint); margin-top: 0.25rem; }
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
                <div class="due-hint">Par défaut: minuit (00:00) si l'heure n'est pas modifiée</div>
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
                <div class="post-option-box">
                    <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer; font-weight:normal;">
                        <input type="checkbox" name="create_post" value="1" style="width:18px;height:18px;">
                        <div>
                            <div class="opt-title">📢 Publier une annonce dans le fil d'actualité</div>
                            <div class="opt-hint">Les étudiants verront une notification dans leur fil d'actualité</div>
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
        updateCounter();

        document.getElementById('status').addEventListener('change', function () {
            document.getElementById('post-option').style.display =
                this.value === 'published' ? 'block' : 'none';
        });
        document.getElementById('status').dispatchEvent(new Event('change'));

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
