@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Paramètres de Mon Profil')

@section('extra-styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}
.profile-card {
    background: #0f172a;
    padding: 2rem;
    border-radius: 8px;
}
.profile-card h2 {
    color: #3b82f6;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #475569;
    padding-bottom: .5rem;
    margin-top: 0;
}
.form-group { margin-bottom: 1.5rem; }
label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
    color: #e2e8f0;
}
input[type="text"],
input[type="email"],
input[type="password"],
input[type="file"],
input[type="number"] {
    width: 100%;
    padding: .75rem;
    border: 1px solid #475569;
    border-radius: 4px;
    font-size: 1rem;
    box-sizing: border-box;
    background: #1e293b;
    color: #e2e8f0;
}
input:focus { outline: none; border-color: #3b82f6; }
.error { color:#dc3545; font-size:.875rem; margin-top: 0.25rem; }
.btn {
    padding:.75rem 1.5rem;
    border:none;
    border-radius:4px;
    cursor:pointer;
    font-weight:700;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
}
.btn-primary { background:#3b82f6; color:#fff; }
.btn-success { background:#10b981; color:#fff; }
.btn-danger  { background:#dc3545; color:#fff; }
.btn-warning { background:#f59e0b; color:#1f2937; }
.info-box {
    background:#0f172a;
    border-left:4px solid #3b82f6;
    padding:1rem;
    margin-bottom:1rem;
    border-radius: 4px;
    font-size: 0.9rem;
}
.success-box {
    background:#0f172a;
    border-left:4px solid #10b981;
    padding:1rem;
    margin-bottom:1rem;
    border-radius: 4px;
}
.current-photo-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.current-photo-row img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #3b82f6;
}
.password-requirements {
    background: #334155;
    border: 1px solid #475569;
    border-radius: 4px;
    padding: 0.75rem 1rem;
    margin-top: 0.5rem;
    font-size: 0.85rem;
}
.password-requirements ul {
    margin: 0.4rem 0 0 1.2rem;
    padding: 0;
    line-height: 1.8;
}
.req { color: #64748b; }
.req.met { color: #28a745; font-weight: bold; }
.strength-bar-wrap {
    height: 5px;
    background: #475569;
    border-radius: 3px;
    margin-top: 0.5rem;
    overflow: hidden;
}
.strength-bar {
    height: 100%;
    width: 0%;
    border-radius: 3px;
    transition: all 0.3s;
}
.email-code-box {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    border-radius: 4px;
    padding: 1rem;
    margin-top: 1rem;
}
.email-code-box input {
    font-size: 1.5rem;
    letter-spacing: 0.3em;
    text-align: center;
    font-family: monospace;
    margin-top: 0.5rem;
}
.warning-box {
    background:#fff3cd;
    border-left:4px solid #ffc107;
    padding:1rem;
    margin-bottom:1rem;
    border-radius: 4px;
}
@media(max-width:768px){
    .profile-grid{grid-template-columns:1fr}
}
</style>
@endsection

@section('content')

@if(session('info'))
    <div class="info-box" style="margin-bottom:1.5rem;">📧 {{ session('info') }}</div>
@endif

<div class="profile-grid">

    <!-- PROFILE INFO -->
    <div class="profile-card">
        <h2>📧 Informations Personnelles</h2>

        @if($user->profile_picture)
            <div class="current-photo-row">
                <img src="{{ $user->profile_picture_url }}" alt="Photo de profil">
                <form method="POST" action="{{ route('profile.delete-picture') }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">🗑️ Supprimer la photo</button>
                </form>
            </div>
        @endif

        <form id="profile-form" method="POST" action="{{ route('profile.update-info') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Photo de profil</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(event)">
                <div id="cropper-container" style="display:none; margin-top:1rem;">
                    <div style="display:flex; justify-content:flex-end; margin-bottom:0.5rem;">
                        <button type="button" onclick="cancelImage()" style="background:#dc3545;color:white;border:none;border-radius:4px;padding:0.4rem 0.8rem;cursor:pointer;font-size:0.85rem;">
                            ✕ Annuler la photo
                        </button>
                    </div>
                    <img id="cropper-preview" style="max-width:100%;">
                </div>
                <input type="hidden" id="crop_x" name="crop_x">
                <input type="hidden" id="crop_y" name="crop_y">
                <input type="hidden" id="crop_width" name="crop_width">
                <input type="hidden" id="crop_height" name="crop_height">
            </div>

            <div class="form-group">
                <label>Nom complet <span style="color:#64748b;font-weight:normal;">(2–20 caractères)</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       minlength="2" maxlength="20" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <button class="btn btn-primary" type="submit" id="profile-submit">✓ Enregistrer le nom</button>
        </form>
    </div>

    <!-- PASSWORD -->
    <div class="profile-card">
        <h2>🔒 Changer le Mot de Passe</h2>

        <form method="POST" action="{{ route('profile.update-password') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Mot de passe actuel</label>
                <input type="password" name="current_password" required>
                @error('current_password') <div class="error">{{ $message }}</div> @enderror
                <div style="text-align: right; margin-top: 0.5rem;">
                    <a href="{{ route('password.forgot') }}" style="color: #3b82f6; font-size: 0.85rem; text-decoration: none;">
                        Mot de passe oublié ?
                    </a>
                </div>
            </div>

            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="new_password" id="new_password"
                       required oninput="checkStrength(this.value)">
                <div class="strength-bar-wrap">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
                <div class="password-requirements">
                    <span style="font-size:0.8rem;color:#94a3b8;">Votre mot de passe doit contenir:</span>
                    <ul>
                        <li class="req" id="req-length">Au moins 8 caractères</li>
                        <li class="req" id="req-upper">Au moins 1 majuscule (A-Z)</li>
                        <li class="req" id="req-lower">Au moins 1 minuscule (a-z)</li>
                        <li class="req" id="req-digit">Au moins 1 chiffre (0-9)</li>
                        <li class="req" id="req-special">Au moins 1 caractère spécial (@$!%*?&)</li>
                    </ul>
                </div>
                @error('new_password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="new_password_confirmation" required>
            </div>

            <button class="btn btn-success" type="submit">🔑 Modifier le mot de passe</button>
        </form>
    </div>

</div>

<!-- EMAIL CHANGE -->
<div class="profile-card" style="margin-top: 2rem;">
    <h2>📬 Changer l'Adresse Email</h2>

    <div class="info-box">
        ℹ️ Un code de confirmation sera envoyé à votre <strong>nouvelle adresse email</strong>.
        Votre email actuel : <strong>{{ $user->email }}</strong>
    </div>

    @if($user->pending_email)
        <div class="warning-box">
            ⏳ Un code a été envoyé à <strong>{{ $user->pending_email }}</strong>.
            Entrez-le ci-dessous pour confirmer le changement.
        </div>
    @endif

    @if(!session('email_code_sent') && !$user->pending_email)
        <form method="POST" action="{{ route('profile.request-email-change') }}" style="max-width: 500px;">
            @csrf
            <div class="form-group">
                <label>Nouvelle adresse email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="nouvelle@email.com" required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-warning" type="submit">📧 Envoyer le code de confirmation</button>
        </form>
    @endif

    @if(session('email_code_sent') || $user->pending_email)
        <form method="POST" action="{{ route('profile.confirm-email-change') }}" style="max-width: 500px;">
            @csrf
            <div class="form-group">
                <label>Code de confirmation <span style="color:#64748b;font-weight:normal;">(6 chiffres)</span></label>
                <div class="email-code-box">
                    <div style="font-size:0.9rem;color:#94a3b8;margin-bottom:0.5rem;">
                        Entrez le code envoyé à <strong>{{ $user->pending_email }}</strong>
                    </div>
                    <input type="text" name="email_code" maxlength="6"
                           placeholder="000000" autocomplete="off" required>
                </div>
                @error('email_code') <div class="error" style="margin-top:0.5rem;">{{ $message }}</div> @enderror
            </div>
            <div style="display:flex;gap:1rem;">
                <button class="btn btn-success" type="submit">✓ Confirmer le changement</button>
                <a href="#" class="btn btn-warning"
                   onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                    🔄 Renvoyer le code
                </a>
            </div>
        </form>

        <form id="resend-form" method="POST" action="{{ route('profile.request-email-change') }}" style="display:none;">
            @csrf
            <input type="hidden" name="email" value="{{ $user->pending_email }}">
        </form>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper;

function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const img = document.getElementById('cropper-preview');
    const container = document.getElementById('cropper-container');
    img.src = URL.createObjectURL(file);
    container.style.display = 'block';
    if (cropper) cropper.destroy();
    cropper = new Cropper(img, { aspectRatio: 1, viewMode: 1, autoCropArea: 1 });
}

function cancelImage() {
    const container = document.getElementById('cropper-container');
    const fileInput = document.getElementById('profile_picture');
    container.style.display = 'none';
    if (cropper) { cropper.destroy(); cropper = null; }
    fileInput.value = '';
}

document.getElementById('profile-form').addEventListener('submit', function () {
    if (cropper) {
        const data = cropper.getData();
        document.getElementById('crop_x').value     = Math.round(data.x);
        document.getElementById('crop_y').value     = Math.round(data.y);
        document.getElementById('crop_width').value  = Math.round(data.width);
        document.getElementById('crop_height').value = Math.round(data.height);
    }
});

function checkStrength(password) {
    const bar  = document.getElementById('strengthBar');
    const reqs = {
        length:  { el: document.getElementById('req-length'),  test: password.length >= 8 },
        upper:   { el: document.getElementById('req-upper'),   test: /[A-Z]/.test(password) },
        lower:   { el: document.getElementById('req-lower'),   test: /[a-z]/.test(password) },
        digit:   { el: document.getElementById('req-digit'),   test: /\d/.test(password) },
        special: { el: document.getElementById('req-special'), test: /[\W_]/.test(password) },
    };

    let score = 0;
    for (const key in reqs) {
        const r = reqs[key];
        r.el.classList.toggle('met', r.test);
        if (r.test) score++;
    }

    bar.style.width = (score / 5 * 100) + '%';
    if (score <= 2)      { bar.style.background = '#dc3545'; }
    else if (score <= 4) { bar.style.background = '#ffc107'; }
    else                 { bar.style.background = '#28a745'; }
}
</script>
@endsection