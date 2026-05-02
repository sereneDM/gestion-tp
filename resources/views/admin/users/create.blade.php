@extends('layouts.app')

@section('title', 'Créer un Utilisateur')
@section('page-title', 'Créer un Nouveau Utilisateur')

@section('extra-styles')
<style>
    .form-container {
        @apply max-w-2xl bg-white dark:bg-slate-800 px-8 py-8 rounded-2xl border border-slate-200 dark:border-slate-700;
    }
    .form-group {
        @apply mb-6;
    }
    label {
        @apply block mb-2 text-slate-900 dark:text-slate-100 font-bold;
    }
    input, select {
        @apply w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-base bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400;
    }
    input::placeholder {
        @apply text-slate-500 dark:text-slate-400;
    }
    input:focus, select:focus {
        @apply outline-none border-violet-500 dark:border-violet-400 ring-2 ring-violet-200 dark:ring-violet-900/30;
    }
    .error {
        @apply text-red-600 dark:text-red-400 text-sm mt-1;
    }
    .info-box {
        @apply bg-blue-50 dark:bg-blue-900/20 border-l-4 border-violet-500 dark:border-violet-400 px-4 py-3 mb-6 rounded-lg text-slate-900 dark:text-slate-200;
    }
    .button-group {
        @apply flex gap-4 mt-8;
    }
    .btn {
        @apply px-6 py-3 border-none rounded-lg cursor-pointer no-underline text-base inline-flex items-center justify-center flex-1 font-medium transition-colors duration-200;
    }
    .btn-primary {
        @apply bg-violet-600 dark:bg-violet-600 text-white hover:bg-violet-700 dark:hover:bg-violet-700;
    }
    .btn-secondary {
        @apply bg-slate-400 dark:bg-slate-600 text-white hover:bg-slate-500 dark:hover:bg-slate-700;
    }
</style>
@endsection

@section('content')
    <div class="form-container">
        <div class="info-box">
            ℹ️ Un mot de passe temporaire sera généré automatiquement et envoyé par email. L'utilisateur devra le changer lors de sa première connexion.
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nom complet *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       required 
                       autofocus>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Rôle *</label>
                <select id="role" name="role" required>
                    <option value="">-- Sélectionner un rôle --</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>
                        Étudiant
                    </option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>
                        Enseignant
                    </option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                        Administrateur
                    </option>
                </select>
                @error('role')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Créer l'utilisateur
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    ✗ Annuler
                </a>
            </div>
        </form>
    </div>
@endsection