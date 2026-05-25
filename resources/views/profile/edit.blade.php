@extends(Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Paramètres du Profil')

@section('breadcrumb')
    <span class="tb-bc-current">Mon Profil</span>
@endsection

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<style>
/* ── Serif heading override (matches feed page) ── */
main h1 {
    font-family: 'DM Serif Display', serif !important;
    font-size: 1.65rem !important;
    font-weight: 400 !important;
    letter-spacing: -0.01em !important;
    color: #0d1117 !important;
}

:root{
    --bg:#f5f7fb;
    --card:#ffffff;
    --line:#e8edf5;

    --text:#0f172a;
    --muted:#64748b;

    --accent:#4f46e5;
    --accent-2:#6366f1;
    --accent-bg:#eef2ff;

    --success:#10b981;
    --danger:#ef4444;
    --warning:#f59e0b;

    --radius:24px;
    --radius-sm:16px;

    --shadow:
        0 10px 30px rgba(15,23,42,0.05);

    --font:'DM Sans',sans-serif;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:var(--bg);
    color:var(--text);
    font-family:var(--font);
}

/* ───────────────── Layout ───────────────── */

.profile-layout{
    max-width:1200px;
    margin:0 auto;
    padding:1rem 0 3rem;

    display:grid;
    grid-template-columns:320px 1fr;
    gap:1.5rem;
    align-items:start;
}

@media(max-width:960px){
    .profile-layout{
        grid-template-columns:1fr;
    }
}

/* ───────────────── Cards ───────────────── */

.card{
    background:var(--card);
    border:1px solid var(--line);
    border-radius:var(--radius);
    overflow:hidden;
    box-shadow:var(--shadow);
}

.profile-layout .card-header{
    padding:1.3rem 1.5rem;
    border-bottom:1px solid var(--line);

    display:flex !important;
    align-items:center !important;
    justify-content:flex-start !important;
    gap:0.75rem;
    background: var(--card) !important;
}

.profile-layout .card-icon{
    width:42px;
    height:42px;

    border-radius:14px;

    background:linear-gradient(
        135deg,
        var(--accent-2),
        var(--accent)
    );

    display:flex;
    align-items:center;
    justify-content:center;

    color:white;
    font-size:18px;

    box-shadow:
        0 8px 20px rgba(79,70,229,0.2);
}

.profile-layout .card-title{
    font-size:0.95rem;
    font-weight:700;
    letter-spacing:-0.02em;
}

.profile-layout .card-subtitle{
    margin-top:2px;
    color:var(--muted);
    font-size:0.78rem;
}

.card-body{
    padding:1.5rem;
}

/* ───────────────── Sidebar ───────────────── */

.profile-sidebar{
    position:sticky;
    top:85px;
}

.profile-sidebar-inner{
    padding:2rem 1.5rem;
}

/* ───────────────── Avatar ───────────────── */

.avatar-wrap{
    display:flex;
    justify-content:center;
    position:relative;
    width:fit-content;
    margin:0 auto;
}

.avatar{
    width:110px;
    height:110px;

    border-radius:50%;
    object-fit:cover;

    border:4px solid white;

    box-shadow:
        0 12px 30px rgba(0,0,0,0.12);
}

.avatar-delete-btn{
    position:absolute;
    top:4px;
    right:4px;

    width:26px;
    height:26px;

    border-radius:50%;

    background:var(--danger);
    color:white;

    border:2px solid white;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:14px;
    font-weight:700;
    line-height:1;

    cursor:pointer;

    box-shadow:0 2px 8px rgba(0,0,0,0.18);

    transition:transform 0.15s ease, background 0.15s ease;
}

.avatar-delete-btn:hover{
    transform:scale(1.12);
    background:#dc2626;
}

/* ───────────────── Profile name/role ───────────────── */

.profile-name{
    text-align:center;
    margin-top:1rem;

    font-size:1.25rem;
    font-weight:800;

    letter-spacing:-0.03em;
}

.profile-role{
    text-align:center;
    margin-top:0.35rem;

    font-size:0.85rem;
    color:var(--muted);
}

.profile-divider{
    height:1px;
    background:var(--line);
    margin:1.5rem 0;
}

.upload-box{
    border:1.5px dashed #cbd5e1;

    border-radius:20px;

    padding:1.1rem;

    background:#fafbff;

    text-align:center;

    transition:0.2s ease;
}

.upload-box:hover{
    border-color:var(--accent);
    background:#f4f7ff;
}

.upload-box input{
    display:none;
}

.upload-trigger{
    cursor:pointer;
    display:block;
}

.upload-trigger i{
    font-size:1.5rem;
    color:var(--accent);
}

.upload-trigger p{
    margin-top:0.5rem;
    font-size:0.82rem;
    color:var(--muted);
}

#cropper-container{
    margin-top:1rem;
}

#cropper-preview{
    max-width:100%;
    border-radius:20px;
}

/* ───────────────── Circular cropper ───────────────── */

.cropper-view-box,
.cropper-face{
    border-radius:50%;
}

/* ───────────────── Right Side ───────────────── */

.settings-column{
    display:flex;
    flex-direction:column;
    gap:1.5rem;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:1rem;
}

@media(max-width:768px){
    .form-grid{
        grid-template-columns:1fr;
    }
}

.form-group{
    display:flex;
    flex-direction:column;
    gap:0.45rem;
}

.form-label{
    font-size:0.72rem;
    font-weight:700;

    letter-spacing:0.08em;
    text-transform:uppercase;

    color:#64748b;
}

.form-input{
    width:100%;

    padding:0.9rem 1rem;

    border-radius:18px;

    border:1px solid #dfe5ef;

    background:white;

    font-size:0.9rem;

    transition:
        border-color 0.2s,
        box-shadow 0.2s;
}

.form-input:focus{
    outline:none;

    border-color:var(--accent);

    box-shadow:
        0 0 0 4px rgba(79,70,229,0.08);
}

/* ───────────────── Buttons ───────────────── */

.actions{
    display:flex;
    gap:0.8rem;
    flex-wrap:wrap;

    margin-top:1.2rem;
}

.btn{
    border:none;

    padding:0.85rem 1.4rem;

    border-radius:18px;

    display:inline-flex;
    align-items:center;

    font-family:var(--font);
    font-weight:700;
    font-size:0.88rem;

    cursor:pointer;

    transition:
        transform 0.15s ease,
        box-shadow 0.2s ease,
        opacity 0.2s ease;
}

.btn:not(:disabled):hover{
    transform:translateY(-2px);
}

.btn:disabled{
    opacity:0.38;
    cursor:not-allowed;
    transform:none !important;
    box-shadow:none !important;
    filter:grayscale(0.2);
}

.btn-primary{
    background:linear-gradient(
        135deg,
        var(--accent-2),
        var(--accent)
    );

    color:white;

    box-shadow:
        0 10px 24px rgba(79,70,229,0.22);
}

.btn-success{
    background:var(--success);
    color:white;

    box-shadow:
        0 10px 24px rgba(16,185,129,0.2);
}

.btn-danger{
    background:#fef2f2;
    color:var(--danger);

    border:1px solid rgba(239,68,68,0.15);
}

/* ───────────────── Password ───────────────── */

.strength-wrap{
    margin-top:0.7rem;
}

.strength-bg{
    height:6px;
    background:#e2e8f0;
    border-radius:999px;
    overflow:hidden;
}

.strength-bar{
    height:100%;
    width:0%;
    border-radius:999px;
    transition:0.25s ease;
}

.password-rules{
    margin-top:1rem;

    background:#f8fafc;

    border:1px solid var(--line);

    border-radius:18px;

    padding:1rem;

    font-size:0.82rem;

    color:var(--muted);

    line-height:1.9;
}

.password-rules li.met{
    color:var(--success);
    font-weight:600;
}

/* ───────────────── Email ───────────────── */

.email-box{
    background:#f8fafc;
    border:1px solid var(--line);

    border-radius:20px;

    padding:1rem;
}

/* ───────────────── Errors ───────────────── */

.error{
    font-size:0.78rem;
    color:var(--danger);

    display:flex;
    align-items:center;
    gap:0.35rem;
}

/* ───────────────── Privacy toggle ───────────────── */

.privacy-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1rem 1.1rem;
    border-radius: 18px;
    border: 1px solid var(--line);
    background: #f8fafc;
    transition: border-color 0.2s, background 0.2s;
}

.privacy-toggle-row:has(input:checked) {
    border-color: rgba(79, 70, 229, 0.3);
    background: var(--accent-bg);
}

.privacy-toggle-info {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.privacy-toggle-title {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text);
}

.privacy-toggle-desc {
    font-size: 0.76rem;
    color: var(--muted);
    line-height: 1.4;
}

/* iOS-style toggle switch */
.toggle-switch {
    position: relative;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}

.toggle-slider {
    position: absolute;
    inset: 0;
    background: #cbd5e1;
    border-radius: 999px;
    cursor: pointer;
    transition: background 0.25s ease;
}

.toggle-slider::before {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    left: 3px;
    top: 3px;
    background: white;
    border-radius: 50%;
    box-shadow: 0 1px 4px rgba(0,0,0,0.18);
    transition: transform 0.25s ease;
}

.toggle-switch input:checked + .toggle-slider {
    background: var(--accent);
}

.toggle-switch input:checked + .toggle-slider::before {
    transform: translateX(20px);
}
</style>
@endsection

@section('content')

@if(Auth::user()->isAdmin())
    <h1 class="page-title">Mon Profil</h1>
    <p class="page-subtitle">Gérez vos informations personnelles et la sécurité de votre compte.</p>
@endif

<div class="profile-layout">

    {{-- SIDEBAR --}}
    <div class="profile-sidebar">

        <div class="card">

            <div class="profile-sidebar-inner">

                <div class="avatar-wrap">
                    <img
                        src="{{ $user->profile_picture_url }}"
                        alt="{{ $user->name }}"
                        class="avatar"
                        id="avatar-preview">

                    @if($user->profile_picture)
                        <button
                            type="button"
                            class="avatar-delete-btn"
                            title="Supprimer la photo"
                            onclick="deletePicture()">&#x2715;</button>
                    @endif
                </div>

                <div class="profile-name">
                    {{ $user->name }}
                </div>

                <div class="profile-role">
                    @if($user->isAdmin())
                        Administrateur
                    @elseif($user->isTeacher())
                        Enseignant
                    @else
                        Étudiant
                    @endif
                </div>

                <div class="profile-divider"></div>

                {{-- PHOTO FORM --}}
                <form
                    id="profile-form"
                    method="POST"
                    action="{{ route('profile.update-picture') }}"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="upload-box">

                        <label class="upload-trigger">

                            <input
                                type="file"
                                name="profile_picture"
                                id="profile_picture"
                                accept="image/*"
                                onchange="previewImage(event)">

                            <i class="ti ti-camera-plus"></i>

                            <p>
                                Modifier la photo
                            </p>

                        </label>

                    </div>

                    <div id="cropper-container" style="display:none;">
                        <img id="cropper-preview">
                    </div>

                    <input type="hidden" name="crop_x" id="crop_x">
                    <input type="hidden" name="crop_y" id="crop_y">
                    <input type="hidden" name="crop_width" id="crop_width">
                    <input type="hidden" name="crop_height" id="crop_height">

                    <div class="actions">

                        <button
                            type="submit"
                            id="save-photo-btn"
                            class="btn btn-primary"
                            disabled>

                            Sauvegarder

                        </button>

                    </div>

                </form>

                {{-- DELETE FORM --}}
                <form
                    id="delete-picture-form"
                    method="POST"
                    action="{{ route('profile.delete-picture') }}"
                    style="display:none;">

                    @csrf
                    @method('DELETE')

                </form>

            </div>

        </div>

    </div>

    {{-- SETTINGS --}}
    <div class="settings-column">

        {{-- PERSONAL INFO --}}
        <div class="card">

            <div class="card-header">

                <div class="card-icon">
                    <i class="ti ti-user"></i>
                </div>

                <div>
                    <div class="card-title">
                        Informations personnelles
                    </div>

                    <div class="card-subtitle">
                        Modifiez vos informations principales
                    </div>
                </div>

            </div>

            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route('profile.update-info') }}"
                    id="info-form">

                    @csrf
                    @method('PUT')

                    <div class="form-grid">

                        <div class="form-group">

                            <label class="form-label">
                                Nom complet
                            </label>

                            <input
                                type="text"
                                class="form-input"
                                name="name"
                                id="name-input"
                                value="{{ old('name',$user->name) }}"
                                minlength="2"
                                maxlength="20"
                                required>

                            @error('name')
                                <div class="error">
                                    <i class="ti ti-alert-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Adresse email
                            </label>

                            <input
                                type="email"
                                class="form-input"
                                value="{{ $user->email }}"
                                disabled>

                        </div>

                    </div>

                    <div class="actions">

                        <button
                            class="btn btn-primary"
                            id="save-info-btn"
                            disabled>

                            Enregistrer

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- PRIVACY (teachers only) --}}
        @if($user->isTeacher())
        <div class="card">

            <div class="card-header">

                <div class="card-icon">
                    <i class="ti ti-shield"></i>
                </div>

                <div>
                    <div class="card-title">
                        Confidentialité
                    </div>

                    <div class="card-subtitle">
                        Gérez ce que les étudiants peuvent voir
                    </div>
                </div>

            </div>

            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route('profile.update-privacy') }}">

                    @csrf
                    @method('PUT')

                    <div class="privacy-toggle-row">

                        <div class="privacy-toggle-info">
                            <div class="privacy-toggle-title">
                                Afficher mon email aux étudiants
                            </div>
                            <div class="privacy-toggle-desc">
                                Votre adresse email sera visible sur la page de vos cours
                            </div>
                        </div>

                        <label class="toggle-switch">
                            <input
                                type="checkbox"
                                name="show_email_publicly"
                                value="1"
                                id="show-email-toggle"
                                {{ $user->show_email_publicly ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            <span class="toggle-slider"></span>
                        </label>

                    </div>

                </form>

            </div>

        </div>
        @endif

        {{-- PASSWORD --}}
        <div class="card">

            <div class="card-header">

                <div class="card-icon">
                    <i class="ti ti-lock"></i>
                </div>

                <div>
                    <div class="card-title">
                        Sécurité du compte
                    </div>

                    <div class="card-subtitle">
                        Modifiez votre mot de passe
                    </div>
                </div>

            </div>

            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route('profile.update-password') }}">

                    @csrf
                    @method('PUT')

                    <div class="form-grid">

                        <div class="form-group">

                            <label class="form-label">
                                Mot de passe actuel
                            </label>

                            <input
                                type="password"
                                class="form-input"
                                name="current_password"
                                id="current-password"
                                oninput="checkPasswordForm()"
                                required>

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Nouveau mot de passe
                            </label>

                            <input
                                type="password"
                                class="form-input"
                                name="new_password"
                                id="new_password"
                                oninput="checkStrength(this.value); checkPasswordForm();"
                                required>

                            <div class="strength-wrap">

                                <div class="strength-bg">
                                    <div class="strength-bar" id="strengthBar"></div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="password-rules">

                        <ul>
                            <li id="req-length">8 caractères minimum</li>
                            <li id="req-upper">1 majuscule</li>
                            <li id="req-lower">1 minuscule</li>
                            <li id="req-digit">1 chiffre</li>
                            <li id="req-special">1 caractère spécial</li>
                        </ul>

                    </div>

                    <div class="actions">

                        <button
                            class="btn btn-success"
                            id="save-password-btn"
                            disabled>

                            Modifier le mot de passe

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- EMAIL --}}
        <div class="card">

            <div class="card-header">

                <div class="card-icon">
                    <i class="ti ti-mail"></i>
                </div>

                <div>
                    <div class="card-title">
                        Changer l'adresse email
                    </div>

                    <div class="card-subtitle">
                        Validation par code de confirmation
                    </div>
                </div>

            </div>

            <div class="card-body">

                <div class="email-box">

                    <form
                        method="POST"
                        action="{{ route('profile.request-email-change') }}">

                        @csrf

                        <div class="form-group">

                            <label class="form-label">
                                Nouvelle adresse email
                            </label>

                            <input
                                type="email"
                                class="form-input"
                                name="email"
                                id="new-email-input"
                                placeholder="nouvelle@email.com"
                                oninput="checkEmailForm()"
                                required>

                        </div>

                        <div class="actions">

                            <button
                                class="btn btn-primary"
                                id="send-email-btn"
                                disabled>

                                Envoyer le code

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let cropper = null;

/* ── Photo ── */

function previewImage(event){
    const file = event.target.files[0];
    if(!file) return;

    const objectUrl = URL.createObjectURL(file);

    const img = document.getElementById('cropper-preview');
    const container = document.getElementById('cropper-container');

    img.src = objectUrl;
    container.style.display = 'block';

    if(cropper){ cropper.destroy(); }

    cropper = new Cropper(img, {
        aspectRatio: 1,
        viewMode: 1,
        autoCropArea: 1,
        responsive: true,
    });

    document.getElementById('save-photo-btn').disabled = false;
}

document.getElementById('profile-form')
.addEventListener('submit', function(){
    if(!cropper) return;

    const data = cropper.getData(true);

    document.getElementById('crop_x').value      = Math.round(data.x);
    document.getElementById('crop_y').value      = Math.round(data.y);
    document.getElementById('crop_width').value  = Math.round(data.width);
    document.getElementById('crop_height').value = Math.round(data.height);
});

function deletePicture(){
    document.getElementById('delete-picture-form').submit();
}

/* ── Personal info ── */

const nameInput    = document.getElementById('name-input');
const saveInfoBtn  = document.getElementById('save-info-btn');
const originalName = nameInput ? nameInput.value : '';

if(nameInput){
    nameInput.addEventListener('input', function(){
        const changed = this.value.trim() !== originalName.trim()
                     && this.value.trim().length >= 2;
        saveInfoBtn.disabled = !changed;
    });
}

/* ── Password ── */

const passwordChecks = {
    length:  /^.{8,}$/,
    upper:   /[A-Z]/,
    lower:   /[a-z]/,
    digit:   /\d/,
    special: /[\W_]/
};

let passwordScore = 0;

function checkStrength(password){
    const bar = document.getElementById('strengthBar');
    let score = 0;

    Object.entries(passwordChecks).forEach(([key, regex]) => {
        const el = document.getElementById('req-' + key);
        if(regex.test(password)){
            score++;
            el.classList.add('met');
        } else {
            el.classList.remove('met');
        }
    });

    passwordScore = score;

    bar.style.width = (score / 5 * 100) + '%';

    if(score <= 2)      bar.style.background = '#ef4444';
    else if(score <= 4) bar.style.background = '#f59e0b';
    else                bar.style.background = '#10b981';
}

function checkPasswordForm(){
    const current = document.getElementById('current-password').value;
    const btn     = document.getElementById('save-password-btn');

    btn.disabled = !(current.length > 0 && passwordScore === 5);
}

/* ── Email ── */

function checkEmailForm(){
    const input = document.getElementById('new-email-input');
    const btn   = document.getElementById('send-email-btn');

    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
    btn.disabled = !valid;
}
</script>

@endsection