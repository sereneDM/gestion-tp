<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
@php
    $siteName        = \App\Models\Setting::get('site_name',        'Plateforme TP');
    $siteDescription = \App\Models\Setting::get('site_description', 'Gérez vos TPs avec clarté');
    $contactEmail    = \App\Models\Setting::get('contact_email',    '');
@endphp
<style>
:root {
  --indigo:     #4f46e5;
  --indigo-dk:  #3730a3;
  --indigo-lt:  #6366f1;
  --accent:     #06b6d4;
  --accent2:    #a78bfa;
  --left-bg:    #f0f1ff;
  --bg-right:   #f6f7ff;
  --text-dark:  #0d0f1a;
  --text-mid:   #4b5563;
  --text-soft:  #94a3b8;
  --border:     #e4e7f0;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: 'DM Sans', sans-serif;
  height: 100vh;
  display: flex;
  background: #fff;
  color: var(--text-dark);
  overflow: hidden;
}

/* ═══════════════════════════════════════
   LEFT PANEL — light
═══════════════════════════════════════ */
.left {
  width: 48%;
  position: relative;
  overflow: hidden;
  background: #eef0ff;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 3rem 3.2rem;
}

/* Soft pastel radial glows */
.left::before {
  content: "";
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 65% 50% at 10% 5%,   rgba(99,102,241,0.18) 0%, transparent 65%),
    radial-gradient(ellipse 50% 45% at 90% 90%,  rgba(6,182,212,0.12)  0%, transparent 65%),
    radial-gradient(ellipse 55% 55% at 75% 15%,  rgba(167,139,250,0.12) 0%, transparent 60%),
    #eef0ff;
  z-index: 0;
}

/* Light grid */
.left::after {
  content: "";
  position: absolute; inset: 0;
  background-image:
    linear-gradient(rgba(99,102,241,0.06) 1px, transparent 1px),
    linear-gradient(90deg, rgba(99,102,241,0.06) 1px, transparent 1px);
  background-size: 40px 40px;
  z-index: 0;
}

/* Decorative blobs */
.blob {
  position: absolute;
  border-radius: 50%;
  z-index: 0;
  filter: blur(40px);
  pointer-events: none;
}
.blob-1 {
  width: 280px; height: 280px;
  background: rgba(99,102,241,0.14);
  top: -80px; right: -60px;
}
.blob-2 {
  width: 200px; height: 200px;
  background: rgba(6,182,212,0.12);
  bottom: 60px; left: -60px;
}
.blob-3 {
  width: 140px; height: 140px;
  background: rgba(167,139,250,0.18);
  bottom: 180px; right: 40px;
}

/* Geo rings (light version) */
.geo {
  position: absolute;
  z-index: 0;
  border-radius: 50%;
}
.geo-1 {
  width: 160px; height: 160px;
  border: 1.5px solid rgba(99,102,241,0.18);
  top: 50px; right: 70px;
  animation: slowSpin 30s linear infinite;
}
.geo-1::after {
  content:"";
  position:absolute; inset: 22px;
  border-radius: 50%;
  border: 1px dashed rgba(99,102,241,0.15);
  animation: slowSpin 18s linear infinite reverse;
}
.geo-2 {
  width: 70px; height: 70px;
  border: 1.5px solid rgba(167,139,250,0.25);
  bottom: 230px; right: 100px;
  animation: slowSpin 22s linear infinite reverse;
}
.geo-dot {
  width: 6px; height: 6px; border-radius: 50%;
  position: absolute; z-index: 0;
  background: var(--indigo-lt);
  box-shadow: 0 0 10px 3px rgba(99,102,241,0.35);
  top: 128px; right: 148px;
  animation: dotPulse 3s ease-in-out infinite;
}

@keyframes slowSpin {
  from { transform: rotate(0deg); }
  to   { transform: rotate(360deg); }
}
@keyframes dotPulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.3; transform: scale(0.5); }
}
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50%       { transform: translateY(-6px); }
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(18px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes shimmer {
  0%, 60% { left: -60%; }
  80%, 100% { left: 120%; }
}
@keyframes barGrow {
  from { width: 0; }
}
@keyframes countUp {
  from { opacity: 0; transform: translateY(4px); }
  to   { opacity: 1; transform: translateY(0); }
}

.left-content {
  position: relative; z-index: 2;
  display: flex; flex-direction: column;
  height: 100%;
  justify-content: space-between;
}

/* ── Brand top ── */
.brand-top { display: flex; flex-direction: column; }

.status-pill {
  display: inline-flex; align-items: center; gap: 7px;
  background: rgba(255,255,255,0.7);
  border: 1px solid rgba(99,102,241,0.2);
  border-radius: 100px;
  padding: 5px 14px 5px 7px;
  margin-bottom: 2rem;
  width: fit-content;
  backdrop-filter: blur(8px);
}
.status-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: #10b981;
  box-shadow: 0 0 7px 2px rgba(16,185,129,0.45);
  animation: dotPulse 2.5s ease-in-out infinite;
}
.status-pill span {
  font-family: 'Sora', sans-serif;
  font-size: 0.68rem; font-weight: 600;
  color: var(--indigo);
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

.brand-headline {
  font-family: 'Sora', sans-serif;
  font-size: 2.5rem; font-weight: 800;
  line-height: 1.15; letter-spacing: -0.04em;
  color: #1e1b4b;
  margin-bottom: 0.9rem;
}
.brand-headline .line2 {
  display: block;
  background: linear-gradient(100deg, #4f46e5 0%, #06b6d4 60%, #a78bfa 100%);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  background-clip: text;
}

.brand-desc {
  font-size: 0.875rem; line-height: 1.7;
  color: #6b7280;
  max-width: 320px;
  margin-bottom: 1.6rem;
}

.divider-line {
  width: 36px; height: 2px;
  background: linear-gradient(90deg, var(--indigo-lt), var(--accent));
  border-radius: 2px;
  margin-bottom: 1.6rem;
}

/* Feature list */
.feature-list { display: flex; flex-direction: column; gap: 11px; margin-bottom: 1.8rem; margin-top: 3rem; }
.feature-item { display: flex; align-items: flex-start; gap: 11px; }
.feature-icon {
  width: 32px; height: 32px; border-radius: 9px;
  background: white;
  border: 1px solid rgba(99,102,241,0.18);
  box-shadow: 0 1px 4px rgba(99,102,241,0.1);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  color: var(--indigo);
  font-size: 15px;
}
.feature-text strong {
  display: block;
  font-family: 'Sora', sans-serif;
  font-size: 0.78rem; font-weight: 600;
  color: #1e1b4b;
  margin-bottom: 1px;
}
.feature-text span { font-size: 0.71rem; color: #9ca3af; line-height: 1.5; }

/* Contact badge */
.contact-badge {
  display: inline-flex; align-items: center; gap: 7px;
  background: white;
  border: 1px solid rgba(99,102,241,0.2);
  border-radius: 10px;
  padding: 7px 13px;
  font-size: 0.73rem; color: var(--indigo);
  text-decoration: none;
  transition: all 0.2s;
  width: fit-content;
  box-shadow: 0 1px 4px rgba(99,102,241,0.08);
}
.contact-badge:hover { background: var(--indigo); color: white; border-color: var(--indigo); }
.contact-badge i { font-size: 14px; }


/* ═══════════════════════════════════════
   RIGHT PANEL
═══════════════════════════════════════ */
.right {
  flex: 1;
  display: flex; align-items: center; justify-content: center;
  padding: 2rem;
  background: var(--bg-right);
  position: relative;
}

.right::before {
  content: "";
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 60% 50% at 50% 0%, rgba(99,102,241,0.06) 0%, transparent 70%),
    radial-gradient(ellipse 40% 40% at 100% 100%, rgba(6,182,212,0.04) 0%, transparent 70%);
  z-index: 0;
}

.login-box {
  position: relative; z-index: 1;
  width: 100%; max-width: 400px;
  animation: fadeUp 0.5s cubic-bezier(0.22,1,0.36,1) both;
}

/* ── Logo centered ── */
.logo-wrap {
  display: flex; flex-direction: column; align-items: center;
  margin-bottom: 2rem;
}
.logo-img-wrap {
  width: 68px; height: 68px; border-radius: 20px;
  background: white;
  box-shadow:
    0 0 0 1px rgba(79,70,229,0.12),
    0 8px 24px rgba(79,70,229,0.14),
    0 2px 6px rgba(0,0,0,0.06);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 1rem; overflow: hidden;
}
.logo-img-wrap img { height: 44px; width: auto; object-fit: contain; }
.logo-title {
  font-family: 'Sora', sans-serif;
  font-size: 1.65rem; font-weight: 800;
  letter-spacing: -0.04em; color: var(--text-dark);
  text-align: center; margin-bottom: 0.2rem;
}
.logo-subtitle { color: var(--text-soft); font-size: 0.82rem; text-align: center; line-height: 1.5; }

.site-desc-chip {
  display: flex; align-items: center; justify-content: center; gap: 5px;
  background: #eef1ff; color: #4f46e5;
  border-radius: 8px; padding: 5px 12px;
  font-size: 11.5px; font-weight: 600;
  margin-bottom: 1.6rem;
  font-family: 'Sora', sans-serif;
}

/* Role selector */
.role-selector {
  display: flex; gap: 5px; margin-bottom: 1.6rem;
  background: #ebebf7; border-radius: 12px; padding: 5px;
}
.role-btn {
  flex: 1; padding: 0.5rem 0.5rem;
  border-radius: 8px; border: none;
  background: transparent;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.78rem; font-weight: 600;
  color: #6366f1; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 5px;
  transition: background 0.15s, color 0.15s, box-shadow 0.15s;
}
.role-btn.active { background: white; color: #4f46e5; box-shadow: 0 1px 6px rgba(79,70,229,0.16); }
.role-btn:not(.active):hover { background: rgba(255,255,255,0.6); }

/* Inputs */
.input-group { margin-bottom: 1rem; }
.input-group label {
  font-family: 'Sora', sans-serif;
  font-size: 0.68rem; font-weight: 700; color: var(--text-soft);
  display: block; margin-bottom: 0.4rem;
  text-transform: uppercase; letter-spacing: 0.08em;
}
.input-wrap { position: relative; display: flex; align-items: center; }
.input-icon { position: absolute; left: 13px; font-size: 15px; color: #c4cde0; pointer-events: none; }
.input-group input {
  width: 100%;
  padding: 0.82rem 1rem 0.82rem 2.5rem;
  border-radius: 11px; border: 1.5px solid var(--border);
  background: white;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.875rem; color: var(--text-dark);
  transition: border-color 0.2s, box-shadow 0.2s;
}
.input-group input:focus {
  border-color: var(--indigo-lt);
  box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
  outline: none;
}
.input-group input::placeholder { color: #d1d8e8; }

.input-meta { display: flex; align-items: center; justify-content: space-between; margin-top: 0.55rem; }
.remember {
  display: flex; align-items: center; gap: 6px;
  font-size: 0.75rem; color: var(--text-soft); cursor: pointer;
}
.remember input[type="checkbox"] { width: 13px; height: 13px; accent-color: var(--indigo-lt); cursor: pointer; }
.error { color: #ef4444; font-size: 0.75rem; margin-top: 0.35rem; }
.forgot a { font-size: 0.75rem; color: var(--indigo-lt); text-decoration: none; }
.forgot a:hover { text-decoration: underline; }

.submit-btn {
  position: relative; overflow: hidden;
  width: 100%; margin-top: 1.4rem; padding: 0.92rem;
  border-radius: 12px; border: none;
  background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
  color: white; font-weight: 700; font-size: 0.88rem;
  font-family: 'Sora', sans-serif; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  box-shadow: 0 4px 14px rgba(79,70,229,0.35), 0 1px 3px rgba(79,70,229,0.2);
  transition: transform 0.15s, box-shadow 0.15s;
}
.submit-btn::after {
  content: ""; position: absolute; top: 0; left: -60%;
  width: 40%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
  transform: skewX(-20deg);
  animation: shimmer 3.5s infinite;
}
.submit-btn:hover  { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(79,70,229,0.4); }
.submit-btn:active { transform: scale(0.99); }

.footer {
  margin-top: 1.6rem; font-size: 0.7rem;
  color: #c4cde0; text-align: center; line-height: 1.7;
}
.footer a { color: #c4cde0; }

@media (max-width: 900px) {
  .left  { display: none; }
  .right { flex: 1; }
}
</style>
</head>
<body>

{{-- ── LEFT ── --}}
<div class="left">
  <span class="blob blob-1"></span>
  <span class="blob blob-2"></span>
  <span class="blob blob-3"></span>
  <span class="geo geo-1"></span>
  <span class="geo geo-2"></span>
  <span class="geo-dot"></span>

  <div class="left-content">

    <div class="brand-top">

      <h1 class="brand-headline">
        Gérez vos TPs
        <span class="line2">avec clarté.</span>
      </h1>



      <div class="divider-line"></div>

      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-icon"><i class="ti ti-upload"></i></div>
          <div class="feature-text">
            <strong>Dépôt de travaux</strong>
            <span>Soumettez vos TPs directement depuis la plateforme</span>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><i class="ti ti-chart-bar"></i></div>
          <div class="feature-text">
            <strong>Suivi de performances</strong>
            <span>Visualisez vos résultats et votre progression en temps réel</span>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><i class="ti ti-users"></i></div>
          <div class="feature-text">
            <strong>Espace collaboratif</strong>
            <span>Travaillez en groupe et interagissez avec vos enseignants</span>
          </div>
        </div>
      </div>


    </div>



  </div>
</div>

{{-- ── RIGHT ── --}}
<div class="right">
  <div class="login-box">

    <div class="logo-wrap">
      <div class="logo-img-wrap">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
      </div>
      <div class="logo-title">Connexion</div>
      <p class="logo-subtitle">Bon retour — sélectionnez votre profil<br>et entrez vos identifiants.</p>
    </div>

    @if($siteDescription)
      <div class="site-desc-chip">
        <i class="ti ti-info-circle" style="font-size:12px;"></i>
        {{ $siteDescription }}
      </div>
    @endif

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

      <div class="input-group">
        <label for="email">Email</label>
        <div class="input-wrap">
          <i class="ti ti-mail input-icon"></i>
          <input type="email" id="email" name="email" value="{{ old('email') }}"
                 placeholder="prenom.nom@univ.tn" required autofocus>
        </div>
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="input-group">
        <label for="password">Mot de passe</label>
        <div class="input-wrap">
          <i class="ti ti-lock input-icon"></i>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
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
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="submit-btn">
        <i class="ti ti-arrow-right"></i> Se connecter
      </button>
    </form>

    <div class="footer">
      {{ $siteName }}
      @if($contactEmail)
        · <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
      @endif
    </div>

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