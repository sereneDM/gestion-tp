@extends('layouts.app')

@section('title', 'Rejoindre un cours')
@section('page-title', 'Rejoindre un Cours')

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

.join-wrapper {
    max-width: 480px;
    margin: 2rem auto;
    padding: 0 0 3rem;
}

.join-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.join-card-top {
    padding: 2.5rem 2rem 1.75rem;
    text-align: center;
    border-bottom: 1px solid var(--line);
}

.join-icon {
    width: 64px; height: 64px;
    border-radius: 20px;
    background: var(--accent-bg);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 28px; color: var(--accent);
}

.join-title {
    font-family: var(--font-serif);
    font-size: 1.5rem; color: var(--ink);
    letter-spacing: -0.01em;
    margin-bottom: 0.4rem;
}

.join-subtitle {
    font-size: 0.85rem; color: var(--ink-3); line-height: 1.5;
}

.join-card-body { padding: 1.75rem 2rem; display: flex; flex-direction: column; gap: 1.25rem; }

.info-box {
    display: flex; align-items: flex-start; gap: 0.65rem;
    background: var(--accent-bg);
    border: 1px solid rgba(61,90,254,0.15);
    border-radius: var(--radius-md);
    padding: 0.85rem 1rem;
    font-size: 0.82rem; color: var(--accent); line-height: 1.5;
}
.info-box i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }

.form-group { display: flex; flex-direction: column; gap: 0.45rem; }

.form-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--ink-3);
}

.code-input {
    width: 100%;
    padding: 0.9rem 1rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 1.3rem;
    font-family: monospace;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-align: center;
    text-transform: uppercase;
    background: var(--surface);
    color: var(--ink);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.code-input::placeholder { color: var(--ink-4); font-weight: 400; letter-spacing: 0.1em; }
.code-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}

.error { font-size: 0.78rem; color: var(--danger); display: flex; align-items: center; gap: 4px; text-align: center; justify-content: center; }
.error i { font-size: 13px; }

.join-card-footer {
    padding: 1.1rem 2rem;
    border-top: 1px solid var(--line);
    background: var(--surface-2);
}

.btn-submit {
    width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    padding: 0.8rem;
    border-radius: var(--radius-md); border: none;
    background: var(--accent); color: white;
    font-size: 0.95rem; font-weight: 700;
    font-family: var(--font-body); cursor: pointer;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
    transition: background 0.2s, transform 0.15s;
}
.btn-submit i { font-size: 16px; }
.btn-submit:hover { background: var(--accent-2); transform: translateY(-1px); }
</style>
@endsection

@section('content')
<div class="join-wrapper">
    <div class="join-card">

        <div class="join-card-top">
            <div class="join-icon"><i class="ti ti-school"></i></div>
            <div class="join-title">Rejoindre un cours</div>
            <div class="join-subtitle">Entrez le code fourni par votre enseignant</div>
        </div>

        <div class="join-card-body">

            <div class="info-box">
                <i class="ti ti-info-circle"></i>
                Le code est au format XXX-XXX-123 — vérifiez auprès de votre enseignant si vous ne l'avez pas.
            </div>

            <form method="POST" action="{{ route('student.join-course') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="join_code">Code d'accès</label>
                    <input type="text" class="code-input"
                           id="join_code" name="join_code"
                           value="{{ old('join_code') }}"
                           placeholder="EQY-ZIH-439"
                           maxlength="11" required autofocus>
                    @error('join_code')
                        <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

        </div>

        <div class="join-card-footer">
                <button type="submit" class="btn-submit">
                    <i class="ti ti-check"></i> Rejoindre le cours
                </button>
            </form>
        </div>

    </div>
</div>
@endsection