@extends('layouts.admin')

@section('title', 'Créer un utilisateur')

@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}" class="tb-bc-page" style="text-decoration:none;">Utilisateurs</a>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">Nouveau</span>
@endsection

@section('content')
<div style="max-width: 560px; margin: 0 auto;">
    <h1 class="page-title">Nouvel utilisateur</h1>
    <p class="page-subtitle">Enregistrez un étudiant, enseignant ou administrateur.</p>

    <div class="card" style="padding: 28px;">
        <div style="display:flex; align-items:flex-start; gap:10px; background:var(--accent-bg); border:1px solid rgba(61,90,254,.15); border-radius:var(--radius-md); padding:12px 14px; margin-bottom:24px; font-size:12.5px; color:var(--accent); line-height:1.5;">
            <i class="ti ti-info-circle" style="font-size:16px; flex-shrink:0; margin-top:1px;"></i>
            Un mot de passe temporaire sera généré et envoyé par e-mail. L'utilisateur devra le changer à sa première connexion.
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="form-group">
                <label class="label" for="name">Nom complet</label>
                <input type="text" id="name" name="name" class="input" value="{{ old('name') }}" required autofocus placeholder="ex : Jean Dupont">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" class="input" value="{{ old('email') }}" required placeholder="jean.dupont@exemple.com">
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="label" for="role">Rôle assigné</label>
                <select id="role" name="role" class="input" required style="appearance:none; background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%239aa3af\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 1rem center; background-size:1rem;">
                    <option value="">-- Sélectionner un rôle --</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Étudiant</option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Enseignant</option>
                    <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Administrateur</option>
                </select>
                @error('role') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Créer le compte</button>
            </div>
        </form>
    </div>
</div>
@endsection