@extends('layouts.app')

@section('title', 'Créer un TP')
@section('page-title', 'Créer un TP pour ' . $course->name)

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.tps.create', $course) }}
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
    --success:    #10b981;
    --success-bg: #ecfdf5;
    --purple:     #7c3aed;
    --purple-bg:  #f3f0ff;
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

.form-wrapper { max-width: 720px; margin: 0 auto; padding: 0.5rem 0 3rem; }

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
.form-card-title   { font-size: 1rem; font-weight: 700; color: var(--ink); }
.form-card-subtitle { font-size: 0.75rem; color: var(--ink-4); margin-top: 1px; }

.form-card-body { padding: 1.75rem 2rem; display: flex; flex-direction: column; gap: 1.25rem; }

.course-pill {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.4rem 0.9rem;
    background: var(--accent-bg);
    border: 1px solid rgba(61,90,254,0.15);
    border-radius: 100px;
    font-size: 0.82rem; color: var(--accent);
}
.course-pill i { font-size: 14px; }

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

.form-hint { font-size: 0.75rem; color: var(--ink-4); }

.error { font-size: 0.75rem; color: var(--danger); display: flex; align-items: center; gap: 4px; }
.error i { font-size: 13px; }

/* ── Enonce box ── */
.enonce-box {
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    overflow: hidden;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.enonce-box:focus-within {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}
.enonce-box textarea {
    width: 100%; min-height: 140px;
    padding: 0.75rem 1rem;
    border: none; border-bottom: 1px solid var(--line);
    border-radius: 0; margin: 0;
    font-size: 0.875rem; font-family: var(--font-body);
    background: var(--surface); color: var(--ink);
    resize: vertical; line-height: 1.6; box-sizing: border-box;
}
.enonce-box textarea:focus { outline: none; box-shadow: none; border-color: var(--line); }
.enonce-box textarea::placeholder { color: var(--ink-4); }

.pdf-section { padding: 1rem; background: var(--surface-2); }
.pdf-section-label {
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--ink-4); margin-bottom: 0.6rem; display: block;
}

/* ── Post option checkbox ── */
.post-option-box {
    display: flex; align-items: flex-start; gap: 0.85rem;
    background: var(--purple-bg);
    border: 1px solid rgba(124,58,237,0.15);
    border-radius: var(--radius-md);
    padding: 1rem 1.1rem;
    cursor: pointer;
}
.post-option-box input[type="checkbox"] {
    width: 18px; height: 18px;
    accent-color: var(--purple);
    flex-shrink: 0; margin-top: 2px; cursor: pointer;
}
.post-option-title { font-size: 0.875rem; font-weight: 600; color: var(--ink); }
.post-option-desc  { font-size: 0.78rem; color: var(--ink-4); margin-top: 2px; }

/* ── Footer ── */
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
    background: var(--surface); color: var(--ink-2);
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
            <div class="form-card-icon"><i class="ti ti-file-plus"></i></div>
            <div>
                <div class="form-card-title">Créer un TP</div>
                <div class="form-card-subtitle">{{ $course->name }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('teacher.courses.tps.store', $course->id) }}" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="form-card-body">

                <div class="course-pill">
                    <i class="ti ti-book"></i> {{ $course->name }}
                </div>

                <div class="form-group">
                    <label class="form-label" for="title">Titre du TP *</label>
                    <input type="text" class="form-input" id="title" name="title"
                           value="{{ old('title') }}"
                           placeholder="Ex: TP1 — Introduction au Machine Learning"
                           maxlength="50" required>
                    <div class="char-counter" id="title-counter">0 / 50</div>
                    @error('title') <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description / Énoncé *</label>
                    <div class="enonce-box">
                        <textarea id="description" name="description" required
                                  placeholder="Décrivez le TP et les objectifs d'apprentissage...">{{ old('description') }}</textarea>
                        <div class="pdf-section">
                            <span class="pdf-section-label">
                                <i class="ti ti-paperclip" style="font-size:11px;"></i>
                                Fichier PDF joint à l'énoncé *
                            </span>
                            <x-file-upload id="attachment" name="attachment" accept=".pdf"
                                           hint="PDF uniquement · max 50 Mo" :required="true" />
                            @error('attachment') <div class="error" style="margin-top:0.5rem;"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="due_date">Date d'échéance <span style="color:var(--ink-4);font-weight:400;text-transform:none;letter-spacing:0;">(optionnel)</span></label>
                    <input type="datetime-local" class="form-input" id="due_date" name="due_date"
                           value="{{ old('due_date', '') }}">
                    <div class="form-hint">Heure optionnelle — par défaut minuit (00:00)</div>
                    @error('due_date') <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Statut *</label>
                    <select class="form-input" id="status" name="status" required>
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Publié — visible aux étudiants</option>
                        <option value="draft"     {{ old('status') === 'draft'     ? 'selected' : '' }}>Brouillon — non visible aux étudiants</option>
                    </select>
                    @error('status') <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                </div>

                <div class="form-group" id="post-option" style="display:none;">
                    <label class="post-option-box">
                        <input type="checkbox" name="create_post" value="1">
                        <div>
                            <div class="post-option-title">Publier une annonce dans le fil d'actualité</div>
                            <div class="post-option-desc">Les étudiants verront une notification dans leur fil d'actualité</div>
                        </div>
                    </label>
                </div>

            </div>

            <div class="form-card-footer">
                <a href="{{ route('teacher.courses.show', $course->id) }}" class="btn-cancel">
                    <i class="ti ti-x"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="ti ti-check"></i> Créer le TP
                </button>
            </div>
        </form>

    </div>
</div>

<script>
const titleInput   = document.getElementById('title');
const titleCounter = document.getElementById('title-counter');
const maxLength    = 50;
function updateCounter() {
    const len = titleInput.value.length;
    titleCounter.textContent = len + ' / ' + maxLength;
    titleCounter.classList.remove('warning', 'danger');
    if (len >= maxLength)            titleCounter.classList.add('danger');
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
const today = new Date();
today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
const todayStr = today.toISOString().slice(0, 10);
dueDateInput.min = todayStr;

// Handle optional time: allow date-only input and default to midnight
const form = document.querySelector('form');
form.addEventListener('submit', function(e) {
    const value = dueDateInput.value.trim();
    
    // If empty, that's fine (optional)
    if (!value) return true;
    
    // If has T, it's a valid datetime-local format - accept as is
    if (value.includes('T')) return true;
    
    // If only date (YYYY-MM-DD), append midnight time
    if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
        dueDateInput.value = value + 'T00:00';
        return true;
    }
    
    // Invalid format
    return true;
        return;
    }
});
</script>
@endsection