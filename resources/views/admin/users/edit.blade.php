@extends('layouts.app')

@section('title', 'Modifier un Utilisateur')

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
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-lg:  16px;
        --radius-xl:  22px;
        --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --font-body:  'DM Sans', sans-serif;
        --font-serif: 'DM Serif Display', serif;
    }

    .form-wrapper {
        max-width: 600px;
        margin: 2rem auto 4rem;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-xl);
        padding: 2.5rem;
        box-shadow: var(--shadow-sm);
    }

    .form-title {
        font-family: var(--font-serif);
        font-size: 1.75rem;
        color: var(--ink);
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: var(--ink-4);
        font-size: 0.875rem;
        margin-bottom: 2rem;
    }

    .info-box {
        background: var(--surface-3);
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        padding: 1rem;
        color: var(--ink-3);
        font-size: 0.82rem;
        line-height: 1.5;
        margin-bottom: 2rem;
        display: flex;
        gap: 0.75rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink-4);
        margin-bottom: 0.5rem;
        letter-spacing: 0.05em;
    }

    .input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        background: var(--surface-2);
        color: var(--ink);
        font-family: var(--font-body);
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .input:focus {
        outline: none;
        border-color: var(--accent);
        background: var(--surface);
        box-shadow: 0 0 0 4px var(--accent-bg);
    }

    .error {
        color: var(--danger);
        font-size: 0.75rem;
        margin-top: 0.4rem;
        font-weight: 600;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
    }

    .btn {
        flex: 1;
        padding: 0.875rem;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--accent);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: var(--accent-2);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(61, 90, 254, 0.2);
    }

    .btn-secondary {
        background: var(--surface);
        color: var(--ink-3);
        border: 1px solid var(--line);
    }

    .btn-secondary:hover {
        background: var(--surface-2);
        color: var(--ink);
    }
</style>
@endsection

@section('content')
<div class="form-wrapper">
    <div class="card">
        <h1 class="form-title">Modifier Profil</h1>
        <p class="form-subtitle">Mise à jour des informations de l'utilisateur.</p>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="label" for="name">Nom complet</label>
                <input type="text" id="name" name="name" class="input" value="{{ old('name', $user->name) }}" required autofocus>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="email">Adresse Email</label>
                <input type="email" id="email" name="email" class="input" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="password">Nouveau mot de passe</label>
                <div class="info-box">
                    <i class="ti ti-info-circle" style="font-size:1.25rem;"></i>
                    <div>Laissez vide pour conserver le mot de passe actuel. Si vous changez le mot de passe, l'utilisateur devra utiliser le nouveau immédiatement.</div>
                </div>
                <input type="password" id="password" name="password" class="input" placeholder="••••••••">
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="role">Rôle assigné</label>
                <select id="role" name="role" class="input" required style="appearance:none; background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%239aa3af\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'%3E%3C/path%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;">
                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Étudiant</option>
                    <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Enseignant</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                </select>
                @error('role') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection
