<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion - Plateforme TP</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', sans-serif;
  height: 100vh;
  display: flex;
  background: #ffffff;
  color: #0f172a;
}

/* ── LEFT SIDE ── */
.left {
  flex: 1;
  background: #4f46e5;
  color: white;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 3rem;
  position: relative;
  overflow: hidden;
}

.left::before {
  content: "";
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(255,255,255,0.07) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.07) 1px, transparent 1px);
  background-size: 36px 36px;
  z-index: 0;
}

.left::after {
  content: "";
  position: absolute;
  width: 380px;
  height: 380px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(139,92,246,0.55), transparent 70%);
  top: -120px;
  right: -120px;
  z-index: 0;
}

.orb {
  position: absolute;
  width: 260px;
  height: 260px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(99,102,241,0.4), transparent 70%);
  bottom: 40px;
  left: -80px;
  z-index: 0;
}

.brand,
.stats,
.preview {
  position: relative;
  z-index: 1;
}

.chip {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255,255,255,0.12);
  border: 0.5px solid rgba(255,255,255,0.22);
  border-radius: 100px;
  padding: 5px 14px 5px 8px;
  margin-bottom: 1.6rem;
}

.chip-dot {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: rgba(255,255,255,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
}

.chip span {
  font-size: 0.75rem;
  font-weight: 600;
  color: rgba(255,255,255,0.9);
  letter-spacing: 0.04em;
}

.brand h1 {
  font-size: 2.2rem;
  font-weight: 800;
  line-height: 1.2;
  letter-spacing: -0.03em;
  margin-bottom: 1rem;
}

.brand h1 em {
  font-style: normal;
  background: linear-gradient(90deg, #a5b4fc, #e879f9);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.brand > p {
  opacity: 0.65;
  line-height: 1.65;
  font-size: 0.88rem;
  max-width: 340px;
}

.stats {
  display: flex;
  gap: 12px;
  margin-top: 2rem;
}

.stat {
  flex: 1;
  background: rgba(255,255,255,0.08);
  border: 0.5px solid rgba(255,255,255,0.15);
  border-radius: 14px;
  padding: 0.85rem 1rem;
}

.stat-val {
  font-size: 1.4rem;
  font-weight: 800;
  color: white;
  letter-spacing: -0.02em;
}

.stat-lbl {
  font-size: 0.7rem;
  color: rgba(255,255,255,0.55);
  margin-top: 3px;
}

.preview {
  background: rgba(255,255,255,0.08);
  border: 0.5px solid rgba(255,255,255,0.15);
  backdrop-filter: blur(12px);
  border-radius: 16px;
  padding: 1.1rem 1.2rem;
}

.preview-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 1rem;
}

.preview-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #a78bfa, #818cf8);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 700;
  color: white;
  flex-shrink: 0;
}

.preview-name {
  font-size: 0.82rem;
  font-weight: 600;
  color: white;
}

.preview-sub {
  font-size: 0.72rem;
  color: rgba(255,255,255,0.5);
}

.preview-score {
  margin-left: auto;
  font-size: 0.75rem;
  font-weight: 700;
  color: #a5b4fc;
}

.bar-row {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.bar-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.bar-label {
  font-size: 0.7rem;
  color: rgba(255,255,255,0.5);
  width: 36px;
  flex-shrink: 0;
}

.bar-track {
  flex: 1;
  height: 5px;
  background: rgba(255,255,255,0.1);
  border-radius: 100px;
  overflow: hidden;
}

.bar-fill {
  height: 100%;
  border-radius: 100px;
  background: linear-gradient(90deg, #a5b4fc, #818cf8);
}

.bar-pct {
  font-size: 0.7rem;
  font-weight: 600;
  color: rgba(255,255,255,0.7);
  width: 28px;
  text-align: right;
}

/* ── RIGHT SIDE ── */
.right {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  background: #fafafa;
}

.login-box {
  width: 100%;
  max-width: 400px;
  padding: 2.8rem;
  background: white;
  border: 1px solid #eef2ff;
  border-radius: 24px;
  box-shadow:
    0 10px 40px rgba(15,23,42,0.07),
    0 2px 8px rgba(15,23,42,0.04);
  animation: fadeUp 0.5s ease both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

.eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: 0.7rem;
  font-weight: 700;
  color: #7c3aed;
  letter-spacing: 0.07em;
  text-transform: uppercase;
  margin-bottom: 0.6rem;
}

.eyebrow-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #7c3aed;
}

.login-box h2 {
  font-size: 1.9rem;
  font-weight: 800;
  letter-spacing: -0.03em;
  margin-bottom: 0.3rem;
  color: #0f172a;
}

.login-box > p {
  color: #64748b;
  font-size: 0.875rem;
  margin-bottom: 2rem;
}

.input-group {
  margin-bottom: 1.2rem;
}

.input-group label {
  font-size: 0.7rem;
  font-weight: 700;
  color: #64748b;
  display: block;
  margin-bottom: 0.4rem;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 13px;
  font-size: 16px;
  color: #94a3b8;
  pointer-events: none;
}

.input-group input {
  width: 100%;
  padding: 0.85rem 1rem 0.85rem 2.5rem;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  font-size: 0.9rem;
  font-family: 'Inter', sans-serif;
  color: #0f172a;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
}

.input-group input:focus {
  background: white;
  border-color: #7c3aed;
  box-shadow: 0 0 0 4px rgba(124,58,237,0.1);
  outline: none;
}

.input-group input::placeholder {
  color: #cbd5e1;
}

.input-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 0.5rem;
}

.remember {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.78rem;
  color: #64748b;
  cursor: pointer;
}

.remember input[type="checkbox"] {
  width: 14px;
  height: 14px;
  accent-color: #7c3aed;
  cursor: pointer;
}

.error {
  color: #ef4444;
  font-size: 0.78rem;
  margin-top: 0.35rem;
}

.forgot a {
  font-size: 0.78rem;
  color: #6366f1;
  text-decoration: none;
}

.forgot a:hover {
  text-decoration: underline;
}

button[type="submit"] {
  position: relative;
  overflow: hidden;
  width: 100%;
  margin-top: 1.2rem;
  padding: 0.95rem;
  border-radius: 12px;
  border: none;
  background: #4f46e5;
  color: white;
  font-weight: 700;
  font-size: 0.92rem;
  font-family: 'Inter', sans-serif;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  letter-spacing: 0.01em;
  transition: background 0.2s, transform 0.15s;
}

button[type="submit"]::after {
  content: "";
  position: absolute;
  top: 0;
  left: -60%;
  width: 40%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
  transform: skewX(-20deg);
  animation: shimmer 3s infinite;
}

@keyframes shimmer {
  0%, 60% { left: -60%; }
  80%, 100% { left: 120%; }
}

button[type="submit"]:hover {
  background: #4338ca;
  transform: translateY(-2px);
}

button[type="submit"]:active {
  transform: scale(0.99);
}

.divider {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 1.1rem 0;
  font-size: 0.75rem;
  color: #94a3b8;
}

.divider::before,
.divider::after {
  content: "";
  flex: 1;
  height: 1px;
  background: #e2e8f0;
}

.btn-sso {
  width: 100%;
  padding: 0.8rem;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  background: white;
  color: #374151;
  font-size: 0.85rem;
  font-weight: 500;
  font-family: 'Inter', sans-serif;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: background 0.15s, border-color 0.15s;
}

.btn-sso:hover {
  background: #f8fafc;
  border-color: #c7d2fe;
}

.footer {
  margin-top: 2rem;
  font-size: 0.72rem;
  color: #94a3b8;
  text-align: center;
  line-height: 1.7;
}

@media (max-width: 900px) {
  .left { display: none; }
  .right { flex: 1; }
}
</style>
</head>
<body>

<!-- LEFT -->
<div class="left">
  <span class="orb"></span>
  <div class="brand">
    <div class="chip">
      <div class="chip-dot"><i class="ti ti-code"></i></div>
      <span>TP Platform</span>
    </div>
    <h1>Gérez vos TPs<br>avec <em>clarté</em></h1>
    <p>Soumettez vos projets, suivez vos performances et collaborez — tout en un seul endroit.</p>
    <div class="stats">
      <div class="stat">
        <div class="stat-val">248</div>
        <div class="stat-lbl">Étudiants</div>
      </div>
      <div class="stat">
        <div class="stat-val">36</div>
        <div class="stat-lbl">TPs actifs</div>
      </div>
      <div class="stat">
        <div class="stat-val">94%</div>
        <div class="stat-lbl">Soumis</div>
      </div>
    </div>
  </div>
  <div class="preview">
    <div class="preview-header">
      <div class="preview-avatar">AS</div>
      <div>
        <div class="preview-name">Amira Salah</div>
        <div class="preview-sub">Informatique · L3</div>
      </div>
      <div class="preview-score">92 pts</div>
    </div>
    <div class="bar-row">
      <div class="bar-item">
        <span class="bar-label">TP 1</span>
        <div class="bar-track"><div class="bar-fill" style="width:88%"></div></div>
        <span class="bar-pct">88%</span>
      </div>
      <div class="bar-item">
        <span class="bar-label">TP 2</span>
        <div class="bar-track"><div class="bar-fill" style="width:95%"></div></div>
        <span class="bar-pct">95%</span>
      </div>
      <div class="bar-item">
        <span class="bar-label">TP 3</span>
        <div class="bar-track"><div class="bar-fill" style="width:72%"></div></div>
        <span class="bar-pct">72%</span>
      </div>
    </div>
  </div>
</div>

<!-- RIGHT -->
<div class="right">
  <div class="login-box">
    <div class="eyebrow"><div class="eyebrow-dot"></div> Espace étudiant</div>
    <h2>Connexion</h2>
    <p>Bon retour — entrez vos identifiants</p>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="input-group">
        <label>Email</label>
        <div class="input-wrap">
          <i class="ti ti-mail input-icon"></i>
          <input type="email" name="email" placeholder="prenom.nom@univ.tn" required>
        </div>
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      <div class="input-group">
        <label>Mot de passe</label>
        <div class="input-wrap">
          <i class="ti ti-lock input-icon"></i>
          <input type="password" name="password" placeholder="••••••••" required>
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
      <button type="submit">
        <i class="ti ti-arrow-right"></i> Se connecter
      </button>
    </form>
    <div class="divider">ou continuer avec</div>
    <button class="btn-sso">
      <i class="ti ti-building-university"></i> SSO Université
    </button>
    <div class="footer">Plateforme de gestion des travaux pratiques</div>
  </div>
</div>

</body>
</html>