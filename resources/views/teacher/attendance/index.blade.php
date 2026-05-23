@extends('layouts.app')

@section('title', 'Prendre les présences')
@section('page-title', 'Prendre les présences')

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.attendance.index') }}
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
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,0.07);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.form-wrapper {
    max-width: 520px;
    margin: 2rem auto;
    padding: 0 0 3rem;
}

.topbar {
    margin-bottom: 1.5rem;
}

.page-heading {
    font-family: var(--font-serif);
    font-size: 1.65rem;
    color: var(--ink);
    letter-spacing: -0.01em;
    margin-bottom: 0.6rem;
}

.form-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
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

.form-card-footer {
    padding: 1.1rem 2rem;
    border-top: 1px solid var(--line);
    background: var(--surface-2);
}

.btn-submit {
    width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    padding: 0.75rem;
    border-radius: var(--radius-md); border: none;
    background: var(--accent); color: white;
    font-size: 0.9rem; font-weight: 700;
    font-family: var(--font-body); cursor: pointer;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
    transition: background 0.2s, transform 0.15s;
}
.btn-submit i { font-size: 16px; }
.btn-submit:hover { background: var(--accent-2); transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="form-wrapper">
    <div class="topbar">
        <h1 class="page-heading">Prendre les présences</h1>
    </div>
    <div class="form-card">

        <div class="form-card-header">
            <div class="form-card-icon"><i class="ti ti-calendar-check"></i></div>
            <div>
                <div class="form-card-subtitle">Sélectionnez un cours et une date</div>
            </div>
        </div>

        <form method="GET" action="{{ route('teacher.attendance.show') }}">

            <div class="form-card-body">

                <div class="form-group">
                    <label class="form-label" for="class_id">Cours *</label>
                    <select class="form-input" id="class_id" name="class_id" required>
                        <option value="">— Choisir un cours —</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">
                                {{ $class->name }} ({{ $class->students->count() }} étudiants)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="date">Date *</label>
                    <input type="date" class="form-input" id="date" name="date"
                           value="{{ date('Y-m-d') }}" required>
                </div>

            </div>

            <div class="form-card-footer">
                <button type="submit" class="btn-submit">
                    <i class="ti ti-arrow-right"></i> Continuer
                </button>
            </div>

        </form>
    </div>
</div>
@endsection