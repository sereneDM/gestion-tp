@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Paramètres de Mon Profil')

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
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
    --danger-bg:  #fff0f0;
    --warning:    #f59e0b;
    --warning-bg: #fffbeb;
    --success:    #10b981;
    --success-bg: #ecfdf5;
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 1000px; margin: 0 auto; padding: 0.5rem 0 3rem; display: flex; flex-direction: column; gap: 1.25rem; }

/* ── Alert boxes ── */
.alert-box {
    display: flex; align-items: flex-start; gap: 0.65rem;
    padding: 0.85rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.85rem; line-height: 1.5;
}
.alert-box i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
.alert-info    { background: var(--accent-bg);  border: 1px solid rgba(61,90,254,0.15); color: var(--accent); }
.alert-warning { background: var(--warning-bg); border: 1px solid rgba(245,158,11,0.2);  color: var(--warning); }

/* ── Grid ── */
.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}

/* ── Card ── */
.card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.card-header {
    padding: 1.25rem 1.75rem 1.1rem;
    border-bottom: 1px solid var(--line);
    display: flex; align-items: center; gap: 0.65rem;
}
.card-header-icon {
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    background: var(--accent-bg);
    display: flex; align-items: center; justify-content: center;
    color: var(--accent); font-size: 16px;
}
.card-header-title { font-size: 0.9rem; font-weight: 700; color: var(--ink); }

.card-body { padding: 1.5rem 1.75rem; display: flex; flex-direction: column; gap: 1.1rem; }
.card-footer {
    padding: 1rem 1.75rem;
    border-top: 1px solid var(--line);
    background: var(--surface-2);
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.5rem;
}

/* ── Avatar row ── */
.avatar-row {
    display: flex; align-items: center; gap: 1rem;
}
.avatar-img {
    width: 72px; height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--line);
    flex-shrink: 0;
}

.btn-danger-sm {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.35rem 0.8rem;
    border-radius: var(--radius-sm);
    border: 1px solid rgba(229,57,53,0.25);
    background: var(--danger-bg);
    color: var(--danger);
    font-size: 0.78rem; font-weight: 600;
    font-family: var(--font-body); cursor: pointer;
    transition: background 0.15s;
}
.btn-danger-sm:hover { background: #ffcdd2; }
.btn-danger-sm i { font-size: 13px; }

/* ── Form elements ── */
.form-group { display: flex; flex-direction: column; gap: 0.45rem; }

.form-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--ink-3);
}
.form-label-note { font-weight: 400; text-transform: none; letter-spacing: 0; color: var(--ink-4); }

.form-input {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 0.875rem; font-family: var(--font-body);
    background: var(--surface); color: var(--ink);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-input::placeholder { color: var(--ink-4); }
.form-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}
input[type="file"].form-input { cursor: pointer; padding: 0.55rem 1rem; }

.error { font-size: 0.75rem; color: var(--danger); display: flex; align-items: center; gap: 4px; }
.error i { font-size: 13px; }

/* ── Cropper container ── */
#cropper-container { margin-top: 0.75rem; }
.cancel-photo-btn {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.3rem 0.75rem;
    background: var(--danger-bg); color: var(--danger);
    border: 1px solid rgba(229,57,53,0.2);
    border-radius: var(--radius-sm);
    font-size: 0.78rem; font-weight: 600;
    font-family: var(--font-body); cursor: pointer;
    margin-bottom: 0.5rem;
}
.cancel-photo-btn i { font-size: 12px; }

/* ── Password strength ── */
.strength-bar-wrap {
    height: 4px;
    background: var(--line);
    border-radius: 4px;
    margin-top: 0.4rem;
    overflow: hidden;
}
.strength-bar { height: 100%; width: 0%; border-radius: 4px; transition: all 0.3s; }

.password-requirements {
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    padding: 0.7rem 1rem;
    font-size: 0.8rem;
}
.password-requirements ul { margin: 0.35rem 0 0 1.1rem; padding: 0; line-height: 1.9; }
.req { color: var(--ink-4); }
.req.met { color: var(--success); font-weight: 600; }

.forgot-link {
    font-size: 0.78rem; color: var(--accent);
    text-decoration: none; text-align: right; display: block;
}
.forgot-link:hover { text-decoration: underline; }

/* ── Email code box ── */
.email-code-box {
    background: var(--warning-bg);
    border: 1px solid rgba(245,158,11,0.25);
    border-radius: var(--radius-md);
    padding: 1rem;
    margin-top: 0.5rem;
}
.email-code-input {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 1.4rem;
    letter-spacing: 0.3em;
    text-align: center;
    font-family: monospace;
    background: var(--surface); color: var(--ink);
    margin-top: 0.5rem;
    transition: border-color 0.2s;
}
.email-code-input:focus { outline: none; border-color: var(--accent); }
.email-code-input::placeholder { color: var(--ink-4); letter-spacing: 0.2em; }

/* ── Buttons ── */
.btn-primary {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.6rem 1.2rem;
    border-radius: var(--radius-md); border: none;
    background: var(--accent); color: white;
    font-size: 0.85rem; font-weight: 700;
    font-family: var(--font-body); cursor: pointer;
    box-shadow: 0 2px 8px rgba(61,90,254,0.25);
    transition: background 0.2s, transform 0.15s;
}
.btn-primary:hover { background: var(--accent-2); transform: translateY(-1px); }
.btn-primary i { font-size: 14px; }

.btn-success {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.6rem 1.2rem;
    border-radius: var(--radius-md); border: none;
    background: var(--success); color: white;
    font-size: 0.85rem; font-weight: 700;
    font-family: var(--font-body); cursor: pointer;
    box-shadow: 0 2px 8px rgba(16,185,129,0.25);
    transition: background 0.2s;
}
.btn-success:hover { background: #0ea572; }
.btn-success i { font-size: 14px; }

.btn-warning {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.6rem 1.2rem;
    border-radius: var(--radius-md); border: none;
    background: var(--warning); color: #1f2937;
    font-size: 0.85rem; font-weight: 700;
    font-family: var(--font-body); cursor: pointer;
    transition: opacity 0.15s;
    text-decoration: none;
}
.btn-warning:hover { opacity: 0.9; }
.btn-warning i { font-size: 14px; }

@media (max-width: 768px) {
    .profile-grid { grid-template-columns: 1fr; }
}
</style>
@endsection

@section('content')
<div class="page-wrapper">

    @if(session('info'))
        <div class="alert-box alert-info">
            <i class="ti ti-info-circle"></i> {{ session('info') }}
        </div>
    @endif

    <div class="profile-grid">

        {{-- ── Personal Info ── --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="ti ti-user"></i></div>
                <div class="card-header-title">Informations personnelles</div>
            </div>

            <form id="profile-form" method="POST" action="{{ route('profile.update-info') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="card-body">

                    @if($user->profile_picture)
                        <div class="avatar-row">
                            <img src="{{ $user->profile_picture_url }}" alt="Photo de profil" class="avatar-img">
                            <form method="POST" action="{{ route('profile.delete-picture') }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger-sm">
                                    <i class="ti ti-trash"></i> Supprimer la photo
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">Photo de profil</label>
                        <input type="file" class="form-input" name="profile_picture" id="profile_picture"
                               accept="image/*" onchange="previewImage(event)">
                        <div id="cropper-container" style="display:none;">
                            <button type="button" class="cancel-photo-btn" onclick="cancelImage()">
                                <i class="ti ti-x"></i> Annuler la photo
                            </button>
                            <img id="cropper-preview" style="max-width:100%;">
                        </div>
                        <input type="hidden" id="crop_x" name="crop_x">
                        <input type="hidden" id="crop_y" name="crop_y">
                        <input type="hidden" id="crop_width" name="crop_width">
                        <input type="hidden" id="crop_height" name="crop_height">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="name">
                            Nom complet <span class="form-label-note">(2–20 caractères)</span>
                        </label>
                        <input type="text" class="form-input" name="name"
                               value="{{ old('name', $user->name) }}"
                               minlength="2" maxlength="20" required>
                        @error('name') <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="card-footer">
                    <button class="btn-primary" type="submit">
                        <i class="ti ti-check"></i> Enregistrer le nom
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Password ── --}}
        <div class="card">
            <div class="card-header">
                <div class="card-header-icon"><i class="ti ti-lock"></i></div>
                <div class="card-header-title">Changer le mot de passe</div>
            </div>

            <form method="POST" action="{{ route('profile.update-password') }}">
                @csrf @method('PUT')

                <div class="card-body">

                    <div class="form-group">
                        <label class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-input" name="current_password" required>
                        @error('current_password') <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                        <a href="{{ route('password.forgot') }}" class="forgot-link">Mot de passe oublié ?</a>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-input" name="new_password" id="new_password"
                               required oninput="checkStrength(this.value)">
                        <div class="strength-bar-wrap">
                            <div class="strength-bar" id="strengthBar"></div>
                        </div>
                        <div class="password-requirements">
                            <span style="font-size:0.75rem;color:var(--ink-4);">Votre mot de passe doit contenir :</span>
                            <ul>
                                <li class="req" id="req-length">Au moins 8 caractères</li>
                                <li class="req" id="req-upper">Au moins 1 majuscule (A-Z)</li>
                                <li class="req" id="req-lower">Au moins 1 minuscule (a-z)</li>
                                <li class="req" id="req-digit">Au moins 1 chiffre (0-9)</li>
                                <li class="req" id="req-special">Au moins 1 caractère spécial (@$!%*?&)</li>
                            </ul>
                        </div>
                        @error('new_password') <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-input" name="new_password_confirmation" required>
                    </div>

                </div>

                <div class="card-footer">
                    <button class="btn-success" type="submit">
                        <i class="ti ti-key"></i> Modifier le mot de passe
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- ── Email Change ── --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-icon"><i class="ti ti-mail"></i></div>
            <div class="card-header-title">Changer l'adresse email</div>
        </div>

        <div class="card-body" style="max-width: 520px;">

            <div class="alert-box alert-info">
                <i class="ti ti-info-circle"></i>
                Un code de confirmation sera envoyé à votre <strong>nouvelle adresse email</strong>.
                Email actuel : <strong>{{ $user->email }}</strong>
            </div>

            @if($user->pending_email)
                <div class="alert-box alert-warning">
                    <i class="ti ti-clock"></i>
                    Un code a été envoyé à <strong>{{ $user->pending_email }}</strong>. Entrez-le ci-dessous pour confirmer.
                </div>
            @endif

            @if(!session('email_code_sent') && !$user->pending_email)
                <form method="POST" action="{{ route('profile.request-email-change') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nouvelle adresse email</label>
                        <input type="email" class="form-input" name="email"
                               value="{{ old('email') }}" placeholder="nouvelle@email.com" required>
                        @error('email') <div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                    </div>
                    <div style="margin-top:0.5rem;">
                        <button type="submit" class="btn-warning">
                            <i class="ti ti-mail-forward"></i> Envoyer le code
                        </button>
                    </div>
                </form>
            @endif

            @if(session('email_code_sent') || $user->pending_email)
                <form method="POST" action="{{ route('profile.confirm-email-change') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">
                            Code de confirmation <span class="form-label-note">(6 chiffres)</span>
                        </label>
                        <div class="email-code-box">
                            <div style="font-size:0.82rem;color:var(--ink-3);">
                                Code envoyé à <strong>{{ $user->pending_email }}</strong>
                            </div>
                            <input type="text" class="email-code-input" name="email_code"
                                   maxlength="6" placeholder="000000" autocomplete="off" required>
                        </div>
                        @error('email_code') <div class="error" style="margin-top:0.4rem;"><i class="ti ti-alert-circle"></i> {{ $message }}</div> @enderror
                    </div>
                    <div style="display:flex;gap:0.75rem;margin-top:0.5rem;flex-wrap:wrap;">
                        <button type="submit" class="btn-success">
                            <i class="ti ti-check"></i> Confirmer le changement
                        </button>
                        <a href="#" class="btn-warning"
                           onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                            <i class="ti ti-refresh"></i> Renvoyer le code
                        </a>
                    </div>
                </form>

                <form id="resend-form" method="POST" action="{{ route('profile.request-email-change') }}" style="display:none;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $user->pending_email }}">
                </form>
            @endif

        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let cropper;

function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const img       = document.getElementById('cropper-preview');
    const container = document.getElementById('cropper-container');
    img.src = URL.createObjectURL(file);
    container.style.display = 'block';
    if (cropper) cropper.destroy();
    cropper = new Cropper(img, { aspectRatio: 1, viewMode: 1, autoCropArea: 1 });
}

function cancelImage() {
    document.getElementById('cropper-container').style.display = 'none';
    if (cropper) { cropper.destroy(); cropper = null; }
    document.getElementById('profile_picture').value = '';
}

document.getElementById('profile-form').addEventListener('submit', function () {
    if (cropper) {
        const data = cropper.getData();
        document.getElementById('crop_x').value      = Math.round(data.x);
        document.getElementById('crop_y').value      = Math.round(data.y);
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
    if      (score <= 2) bar.style.background = '#e53935';
    else if (score <= 4) bar.style.background = '#f59e0b';
    else                 bar.style.background = '#10b981';
}
</script>
@endsection