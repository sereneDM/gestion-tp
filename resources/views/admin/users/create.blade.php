@extends('layouts.app')

@section('title', 'Créer un Utilisateur')
@section('page-title', 'Créer un Nouveau Utilisateur')

@section('extra-styles')
<style>
    .form-container {
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
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #007bff;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
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
        background-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
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