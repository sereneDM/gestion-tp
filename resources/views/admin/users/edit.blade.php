@extends('layouts.app')

@section('title', 'Modifier un Utilisateur')
@section('page-title', 'Modifier un Utilisateur')

@section('extra-styles')
<style>
    .form-container {
        @apply bg-white dark:bg-slate-800 px-8 py-8 rounded-2xl shadow-md dark:shadow-lg max-w-2xl border border-slate-200 dark:border-slate-700;
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
    .info {
        @apply bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded-lg text-sm text-slate-900 dark:text-slate-200 mb-4 border border-blue-300 dark:border-blue-700;
    }
    .button-group {
        @apply flex gap-4 mt-8;
    }
    .btn {
        @apply px-6 py-3 border-none rounded-lg cursor-pointer no-underline text-base inline-flex items-center justify-center flex-1 font-medium transition-colors duration-200;
    }
    .btn-primary {
        @apply bg-amber-500 dark:bg-amber-600 text-slate-900 dark:text-white hover:bg-amber-600 dark:hover:bg-amber-700;
    }
    .btn-secondary {
        @apply bg-slate-400 dark:bg-slate-600 text-white hover:bg-slate-500 dark:hover:bg-slate-700;
    }
</style>
@endsection

@section('content')
    <div class="form-container">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nom complet *</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <div class="info">
                    ℹ️ Laissez vide pour conserver le mot de passe actuel
                </div>
                <input type="password"
                       id="password"
                       name="password"
                       >
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Rôle *</label>
                <select id="role" name="role" required>
                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>
                        Étudiant
                    </option>
                    <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>
                        Enseignant
                    </option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                        Administrateur
                    </option>
                </select>
                @error('role')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Enregistrer les modifications
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    ✗ Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
