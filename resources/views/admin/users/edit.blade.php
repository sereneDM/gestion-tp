@extends('layouts.admin')

@section('title', 'Modifier utilisateur')

@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}" class="tb-bc-page" style="text-decoration:none;">Utilisateurs</a>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">Modifier</span>
@endsection

@section('content')
<div style="max-width: 560px; margin: 0 auto;">
    <h1 class="page-title">Modifier le profil</h1>
    <p class="page-subtitle">Mise à jour des informations de l'utilisateur.</p>

    <div class="card" style="padding: 28px;">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="label" for="name">Nom complet</label>
                <input type="text" id="name" name="name" class="input" value="{{ old('name', $user->name) }}" required autofocus>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" class="input" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="password">Nouveau mot de passe</label>
                <div style="display:flex; align-items:flex-start; gap:10px; background:var(--surface-3); border:1px solid var(--line); border-radius:var(--radius-md); padding:10px 14px; margin-bottom:10px; font-size:12px; color:var(--ink-3); line-height:1.5;">
                    <i class="ti ti-info-circle" style="font-size:15px; flex-shrink:0; margin-top:1px;"></i>
                    Laissez vide pour conserver le mot de passe actuel.
                </div>
                <input type="password" id="password" name="password" class="input" placeholder="••••••••">
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="role">Rôle assigné</label>
                <select id="role" name="role" class="input" required style="appearance:none; background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%239aa3af\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 1rem center; background-size:1rem;">
                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Étudiant</option>
                    <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Enseignant</option>
                    <option value="admin"   {{ $user->role === 'admin'   ? 'selected' : '' }}>Administrateur</option>
                </select>
                @error('role') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
