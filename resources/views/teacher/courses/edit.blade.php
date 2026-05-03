@extends('layouts.app')
@section('title', 'Modifier le Cours')
@section('page-title', 'Modifier le Cours')
@section('extra-styles')
<style>
    .form-container {
        background: var(--tp-bg-raised);
        padding: 2rem;
        border-radius: 1rem;
        border: 1px solid var(--tp-border);
        max-width: 800px;
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
    textarea { min-height: 120px; resize: vertical; }
    input::placeholder, textarea::placeholder { color: var(--tp-text-faint); }
    input:focus, textarea:focus, select:focus { outline: none; border-color:
    select option { background: var(--tp-input-bg); color: var(--tp-text-primary); }
    .error { color:
    [data-theme="dark"] .error { color:
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
</style>
@endsection
@section('content')
    <div class="form-container">
        <form method="POST" action="{{ route('teacher.courses.update', $course->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="from" value="{{ request()->query('from', 'info') }}">
            <div class="form-group">
                <label for="name">Nom du cours *</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $course->name) }}"
                       maxlength="50"
                       required>
                <div class="char-counter" id="name-counter">0 / 50</div>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="status">Statut *</label>
                <select id="status" name="status" required>
                    <option value="active" {{ old('status', $course->status) === 'active' ? 'selected' : '' }}>
                        Actif
                    </option>
                    <option value="archived" {{ old('status', $course->status) === 'archived' ? 'selected' : '' }}>
                        Archivé
                    </option>
                </select>
                @error('status')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Enregistrer les modifications
                </button>
                <a href="{{ route('teacher.courses.show', $course->id) }}?tab={{ request()->query('from', 'info') }}" class="btn btn-secondary">
                    ✗ Annuler
                </a>
            </div>
        </form>
    </div>
    <script>
        const nameInput   = document.getElementById('name');
        const nameCounter = document.getElementById('name-counter');
        const maxLength   = 50;
        function updateCounter() {
            const len = nameInput.value.length;
            nameCounter.textContent = len + ' / ' + maxLength;
            nameCounter.classList.remove('warning', 'danger');
            if (len >= maxLength)             nameCounter.classList.add('danger');
            else if (len >= maxLength * 0.8)  nameCounter.classList.add('warning');
        }
        nameInput.addEventListener('input', updateCounter);
        updateCounter();
    </script>
@endsection