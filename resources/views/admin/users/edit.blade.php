@extends('layouts.admin')

@section('title', 'Modifier un Utilisateur')
@section('page-title', 'Modifier un Utilisateur')

@section('extra-styles')
<style>
    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 600px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: bold;
    }
    input, select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    input:focus, select:focus {
        outline: none;
        border-color: #007bff;
    }
    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .info {
        background-color: #e7f3ff;
        padding: 0.75rem;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #0056b3;
        margin-bottom: 1rem;
    }
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        display: inline-block;
        flex: 1;
        text-align: center;
    }
    .btn-primary {
        background-color: #ffc107;
        color: #333;
    }
    .btn-primary:hover {
        background-color: #e0a800;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #545b62;
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
