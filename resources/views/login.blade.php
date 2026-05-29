<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
@php
    $siteName        = \App\Models\Setting::get('site_name',        'Plateforme TP');
    $siteDescription = \App\Models\Setting::get('site_description', 'Gérez vos TPs avec clarté');
    $contactEmail    = \App\Models\Setting::get('contact_email',    '');
@endphp
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'Inter', sans-serif;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  /* Deep ocean-to-sky gradient base */
  background: linear-gradient(160deg, #0a1628 0%, #0d2d5e 35%, #1a5fa8 65%, #2dd4bf 100%);
}

/* ── Animated mesh blobs ── */
.blob {
  position: fixed;
  border-radius: 50%;
  filter: blur(72px);
  opacity: 0.45;
  pointer-events: none;
  z-index: 0;
  animation: drift linear infinite;
}
.blob-1 {
  width: 600px; height: 600px;
  background: #1d4ed8;
  top: -180px; left: -140px;
  animation-duration: 18s;
  animation-delay: 0s;
}
.blob-2 {
  width: 480px; height: 480px;
  background: #0ea5e9;
  bottom: -120px; right: -100px;
  animation-duration: 22s;
  animation-delay: -6s;
}
.blob-3 {
  width: 360px; height: 360px;
  background: #06b6d4;
  top: 40%; left: 50%;
  transform: translate(-50%, -50%);
  animation-duration: 26s;
  animation-delay: -12s;
}
.blob-4 {
  width: 280px; height: 280px;
  background: #38bdf8;
  bottom: 10%; left: 8%;
  animation-duration: 20s;
  animation-delay: -4s;
}

@keyframes drift {
  0%   { transform: translate(0px, 0px) scale(1); }
  25%  { transform: translate(30px, -20px) scale(1.04); }
  50%  { transform: translate(-20px, 30px) scale(0.97); }
  75%  { transform: translate(20px, 15px) scale(1.02); }
  100% { transform: translate(0px, 0px) scale(1); }
}

/* Subtle grid overlay */
body::after {
  content: "";
  position: fixed; inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
  background-size: 48px 48px;
  z-index: 1;
  pointer-events: none;
}

/* ── Card ── */
.card {
  position: relative; z-index: 2;
  width: 100%; max-width: 430px;
  margin: 2rem 1rem;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.22);
  border-radius: 28px;
  padding: 2.4rem 2.4rem 2rem;
  backdrop-filter: blur(40px);
  -webkit-backdrop-filter: blur(40px);
  box-shadow:
    0 24px 64px rgba(0, 0, 0, 0.25),
    0 1px 0 rgba(255,255,255,0.15) inset;
  animation: fadeUp 0.45s ease both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Logo — centered ── */
.logo-area {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 1.5rem;
}

.logo-img {
  height: 52px; width: auto;
  object-fit: contain;
  filter: drop-shadow(0 2px 8px rgba(0,0,0,0.2));
}

.logo-icon-fallback {
  display: none;
  width: 50px; height: 50px; border-radius: 14px;
  background: rgba(37,99,235,0.75);
  border: 1px solid rgba(255,255,255,0.3);
  align-items: center; justify-content: center;
  color: #fff; font-size: 22px;
}

/* ── Heading ── */
.card h2 {
  font-size: 1.7rem; font-weight: 800;
  letter-spacing: -0.03em; color: #fff;
  margin-bottom: 0.3rem; text-align: center;
}

.subtitle {
  font-size: 13px; color: rgba(255,255,255,0.55);
  margin-bottom: 1.5rem; line-height: 1.55;
  text-align: center;
}

/* ── Description chip ── */
.chip-wrap { display: flex; justify-content: center; margin-bottom: 1.5rem; }

.desc-chip {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(59,130,246,0.25);
  border: 0.5px solid rgba(147,197,253,0.4);
  border-radius: 8px; padding: 5px 13px;
  font-size: 11.5px; font-weight: 600; color: #bfdbfe;
}

/* ── Role selector ── */
.role-selector {
  display: flex; gap: 4px;
  background: rgba(255,255,255,0.07);
  border: 0.5px solid rgba(255,255,255,0.15);
  border-radius: 14px; padding: 4px;
  margin-bottom: 1.5rem;
}

.role-btn {
  flex: 1; padding: 8px 4px;
  border-radius: 10px; border: none;
  background: transparent;
  font-size: 12px; font-weight: 600;
  font-family: 'Inter', sans-serif;
  color: rgba(255,255,255,0.5); cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 5px;
  transition: background 0.15s, color 0.15s;
}

.role-btn.active {
  background: rgba(255,255,255,0.15);
  color: #fff;
  border: 0.5px solid rgba(255,255,255,0.25);
  box-shadow: 0 1px 6px rgba(0,0,0,0.15);
}

.role-btn:not(.active):hover {
  background: rgba(255,255,255,0.09);
  color: rgba(255,255,255,0.75);
}

/* ── Inputs ── */
.input-group { margin-bottom: 1.1rem; }

.input-group label {
  display: block;
  font-size: 10.5px; font-weight: 700;
  color: rgba(255,255,255,0.4);
  text-transform: uppercase; letter-spacing: 0.08em;
  margin-bottom: 6px;
}

.input-wrap { position: relative; display: flex; align-items: center; }

.input-icon {
  position: absolute; left: 13px;
  font-size: 15px; color: rgba(147,197,253,0.7);
  pointer-events: none;
}

.input-group input {
  width: 100%;
  padding: 12px 14px 12px 38px;
  background: rgba(255,255,255,0.08);
  border: 0.5px solid rgba(255,255,255,0.15);
  border-radius: 12px;
  font-size: 14px; font-family: 'Inter', sans-serif;
  color: #fff; outline: none;
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}

.input-group input:focus {
  border-color: rgba(96,165,250,0.7);
  background: rgba(255,255,255,0.13);
  box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
}

.input-group input::placeholder { color: rgba(255,255,255,0.2); }

.error-msg {
  color: #fca5a5; font-size: 11.5px; margin-top: 5px;
  display: flex; align-items: center; gap: 4px;
}

/* ── Remember / forgot ── */
.input-meta {
  display: flex; align-items: center; justify-content: space-between;
  margin-top: 8px;
}

.remember {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; color: rgba(255,255,255,0.4);
  cursor: pointer; user-select: none;
}

.remember input[type="checkbox"] {
  width: 14px; height: 14px;
  accent-color: #3b82f6; cursor: pointer;
}

.forgot a {
  font-size: 12px; color: #93c5fd;
  font-weight: 500; text-decoration: none;
}
.forgot a:hover { color: #bfdbfe; }

/* ── Submit ── */
.submit-btn {
  width: 100%; margin-top: 1.5rem; padding: 13px;
  border-radius: 14px;
  border: 1px solid rgba(96,165,250,0.4);
  background: rgba(37,99,235,0.75);
  color: #fff; font-weight: 700; font-size: 14px;
  font-family: 'Inter', sans-serif; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: background 0.2s, transform 0.15s;
  backdrop-filter: blur(8px);
  box-shadow: 0 4px 20px rgba(37,99,235,0.4);
  position: relative; overflow: hidden;
}

.submit-btn::after {
  content: "";
  position: absolute; top: 0; left: -60%;
  width: 40%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
  transform: skewX(-20deg);
  animation: shimmer 3.5s infinite;
}

@keyframes shimmer {
  0%,  55% { left: -60%; }
  80%, 100% { left: 130%; }
}

.submit-btn:hover  { background: rgba(37,99,235,0.92); transform: translateY(-1px); }
.submit-btn:active { transform: scale(0.99); }

/* ── Footer ── */
.footer {
  margin-top: 1.5rem;
  font-size: 11px; color: rgba(255,255,255,0.25);
  text-align: center; line-height: 1.7;
}
.footer a { color: rgba(255,255,255,0.3); text-decoration: none; }
.footer a:hover { color: rgba(255,255,255,0.55); }

/* ── Responsive ── */
@media (max-width: 480px) {
  .card { padding: 1.7rem 1.5rem 1.5rem; border-radius: 22px; }
  .card h2 { font-size: 1.5rem; }
}
</style>
</head>
<body>

<!-- Animated blobs -->
<span class="blob blob-1"></span>
<span class="blob blob-2"></span>
<span class="blob blob-3"></span>
<span class="blob blob-4"></span>

<div class="card">

  {{-- Logo — centered --}}
  <div class="logo-area">
    <img src="{{ asset('images/logo.png') }}" alt="{{ $siteName }}" class="logo-img"
         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    <span class="logo-icon-fallback"><i class="ti ti-code"></i></span>
  </div>

  <h2>Connexion</h2>
  <p class="subtitle">Bon retour — sélectionnez votre profil et entrez vos identifiants.</p>

  {{-- Site description chip --}}
  @if($siteDescription)
    <div class="chip-wrap">
      <div class="desc-chip">
        <i class="ti ti-sparkles" style="font-size:12px;"></i>
        {{ $siteDescription }}
      </div>
    </div>
  @endif

  {{-- Role selector --}}
  <div class="role-selector" role="group" aria-label="Type de compte">
    <button type="button" class="role-btn active" data-role="etudiant" onclick="setRole(this)">
      <i class="ti ti-school"></i> Étudiant
    </button>
    <button type="button" class="role-btn" data-role="enseignant" onclick="setRole(this)">
      <i class="ti ti-chalkboard"></i> Enseignant
    </button>
    <button type="button" class="role-btn" data-role="admin" onclick="setRole(this)">
      <i class="ti ti-shield"></i> Admin
    </button>
  </div>

  <form method="POST" action="{{ route('login') }}">
    @csrf
    <input type="hidden" name="role" id="role-input" value="etudiant">

    {{-- Email --}}
    <div class="input-group">
      <label for="email">Email</label>
      <div class="input-wrap">
        <i class="ti ti-mail input-icon"></i>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               placeholder="prenom.nom@univ.tn"
               required autofocus autocomplete="email">
      </div>
      @error('email')
        <div class="error-msg"><i class="ti ti-alert-circle" style="font-size:13px;"></i> {{ $message }}</div>
      @enderror
    </div>

    {{-- Password --}}
    <div class="input-group">
      <label for="password">Mot de passe</label>
      <div class="input-wrap">
        <i class="ti ti-lock input-icon"></i>
        <input type="password" id="password" name="password"
               placeholder="••••••••" required autocomplete="current-password">
      </div>
      <div class="input-meta">
        <label class="remember">
          <input type="checkbox" name="remember"> Se souvenir de moi
        </label>
        <div class="forgot">
          <a href="{{ route('password.forgot') }}">Mot de passe oublié ?</a>
        </div>
      </div>
      @error('password')
        <div class="error-msg"><i class="ti ti-alert-circle" style="font-size:13px;"></i> {{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="submit-btn">
      <i class="ti ti-arrow-right"></i> Se connecter
    </button>
  </form>

  <div class="footer">
    {{ $siteName }}
    @if($contactEmail)
      &nbsp;·&nbsp; <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
    @endif
  </div>

</div>

<script>
function setRole(btn) {
  document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('role-input').value = btn.dataset.role;
}
</script>

</body>
</html>