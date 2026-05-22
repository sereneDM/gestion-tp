@extends('layouts.app')

@section('title', 'Modifier le Cours')
@section('page-title', 'Modifier le Cours')

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.courses.edit', $course) }}
@endsection

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
:root {
    --ink:        #0d1117;
    --ink-2:      #3d4550;
    --ink-3:      #6b7585;
    --ink-4:      #9aa3af;
    --line:       #e8ebef;
    --line-2:     #d1d6dd;
    --surface:    #ffffff;
    --surface-2:  #f5f6f8;
    --surface-3:  #eef0f3;
    --accent:     #3d5afe;
    --accent-2:   #5271ff;
    --accent-bg:  #eef1ff;
    --danger:     #e53935;
    --warning:    #f59e0b;
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.form-wrapper { max-width: 680px; margin: 0 auto; padding: 0.5rem 0 3rem; }

.form-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.form-card-header {
    padding: 1.5rem 2rem 1.25rem;
    border-bottom: 1px solid var(--line);
    display: flex; align-items: center; gap: 0.75rem;
}
.form-card-icon {
    width: 38px; height: 38px;
    border-radius: var(--radius-md);
    background: var(--accent-bg);
    display: flex; align-items: center; justify-content: center;
    color: var(--accent); font-size: 18px;
}
.form-card-title { font-size: 1rem; font-weight: 700; color: var(--ink); }
.form-card-subtitle { font-size: 0.75rem; color: var(--ink-4); margin-top: 1px; }

.form-card-body { padding: 1.75rem 2rem; display: flex; flex-direction: column; gap: 1.25rem; }

.form-group { display: flex; flex-direction: column; gap: 0.45rem; }

.form-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--ink-3);
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-family: var(--font-body);
    background: var(--surface);
    color: var(--ink);
    transition: border-color 0.2s, box-shadow 0.2s;
    appearance: none;
}
.form-input::placeholder { color: var(--ink-4); }
.form-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}
textarea.form-input { min-height: 110px; resize: vertical; line-height: 1.6; }
select.form-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7585' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 2.5rem;
    cursor: pointer;
}

.char-counter { text-align: right; font-size: 0.7rem; color: var(--ink-4); transition: color 0.2s; }
.char-counter.warning { color: var(--warning); }
.char-counter.danger  { color: var(--danger); }

.error { font-size: 0.75rem; color: var(--danger); display: flex; align-items: center; gap: 4px; }
.error i { font-size: 13px; }

.form-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.1rem 2rem;
    border-top: 1px solid var(--line);
    background: var(--surface-2);
}

.btn-cancel {
    padding: 0.6rem 1.1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--surface);
    color: var(--ink-2);
    font-size: 0.875rem; font-weight: 500;
    font-family: var(--font-body);
    text-decoration: none; cursor: pointer;
    transition: background 0.15s;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-cancel:hover { background: var(--surface-3); }
.btn-cancel i { font-size: 15px; }

.btn-submit {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.65rem 1.4rem;
    border-radius: var(--radius-md); border: none;
    background: var(--accent); color: white;
    font-size: 0.875rem; font-weight: 700;
    font-family: var(--font-body); cursor: pointer;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
    transition: background 0.2s, transform 0.15s;
}
.btn-submit i { font-size: 15px; }
.btn-submit:hover { background: var(--accent-2); transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="form-wrapper">
    <div class="form-card">

        <div class="form-card-header">
            <div class="form-card-icon"><i class="ti ti-edit"></i></div>
            <div>
                <div class="form-card-title">Modifier le cours</div>
                <div class="form-card-subtitle">{{ $course->name }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('teacher.courses.update', $course->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="from" value="{{ request()->query('from', 'info') }}">

            <div class="form-card-body">

                <div class="form-group">
                    <label class="form-label" for="name">Nom du cours *</label>
                    <input type="text" class="form-input" id="name" name="name"
                           value="{{ old('name', $course->name) }}"
                           maxlength="50" required>
                    <div class="char-counter" id="name-counter">0 / 50</div>
                    @error('name')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description <span style="color:var(--ink-4);font-weight:400;text-transform:none;letter-spacing:0;">(optionnel)</span></label>
                    <textarea class="form-input" id="description" name="description">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Statut *</label>
                    <select class="form-input" id="status" name="status" required>
                        <option value="active"   {{ old('status', $course->status) === 'active'   ? 'selected' : '' }}>Actif</option>
                        <option value="archived" {{ old('status', $course->status) === 'archived' ? 'selected' : '' }}>Archivé</option>
                    </select>
                    @error('status')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="course_pdf">Fichier du cours (PDF) <span style="color:var(--ink-4);font-weight:400;text-transform:none;letter-spacing:0;">(optionnel)</span></label>
                    <x-file-upload id="course_pdf" name="course_pdf" accept=".pdf" hint="PDF uniquement · max 50 Mo" :required="false" />
                    @if($course->course_pdf)
                        <div style="margin-top:6px; font-size:0.9rem; color:var(--ink-3);">
                            Fichier actuel: <a href="{{ asset('storage/' . $course->course_pdf) }}" target="_blank">Voir le PDF</a>
                        </div>
                    @endif
                    @error('course_pdf')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-card-footer">
                <a href="{{ route('teacher.courses.show', $course->id) }}?tab={{ request()->query('from', 'info') }}" class="btn-cancel">
                    <i class="ti ti-x"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="ti ti-device-floppy"></i> Enregistrer
                </button>
            </div>
        </form>

    </div>
</div>

<script>
const nameInput   = document.getElementById('name');
const nameCounter = document.getElementById('name-counter');
const maxLength   = 50;
function updateCounter() {
    const len = nameInput.value.length;
    nameCounter.textContent = len + ' / ' + maxLength;
    nameCounter.classList.remove('warning', 'danger');
    if (len >= maxLength)            nameCounter.classList.add('danger');
    else if (len >= maxLength * 0.8) nameCounter.classList.add('warning');
}
nameInput.addEventListener('input', updateCounter);
updateCounter();
</script>
@endsection