<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme TP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #020817;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* ── Galaxy background ── */
        .stars {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(139, 92, 246, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 60% 80%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
                #020817;
        }

        .stars::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 10% 15%, rgba(255,255,255,0.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 25% 40%, rgba(255,255,255,0.5) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 40% 10%, rgba(255,255,255,0.8) 0%, transparent 100%),
                radial-gradient(1px 1px at 55% 60%, rgba(255,255,255,0.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 70% 25%, rgba(255,255,255,0.6) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 80% 70%, rgba(255,255,255,0.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 45%, rgba(255,255,255,0.5) 0%, transparent 100%),
                radial-gradient(1px 1px at 15% 75%, rgba(255,255,255,0.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 35% 85%, rgba(255,255,255,0.4) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 50% 35%, rgba(255,255,255,0.8) 0%, transparent 100%),
                radial-gradient(1px 1px at 65% 90%, rgba(255,255,255,0.5) 0%, transparent 100%),
                radial-gradient(1px 1px at 85% 10%, rgba(255,255,255,0.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 5% 55%, rgba(255,255,255,0.4) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 95% 80%, rgba(255,255,255,0.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 45% 65%, rgba(255,255,255,0.5) 0%, transparent 100%),
                radial-gradient(1px 1px at 75% 50%, rgba(255,255,255,0.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 20% 90%, rgba(255,255,255,0.4) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 60% 5%, rgba(255,255,255,0.8) 0%, transparent 100%),
                radial-gradient(1px 1px at 30% 20%, rgba(255,255,255,0.5) 0%, transparent 100%),
                radial-gradient(1px 1px at 88% 35%, rgba(255,255,255,0.6) 0%, transparent 100%);
        }

        .stars::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 12% 30%, rgba(167,139,250,0.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 38% 55%, rgba(129,140,248,0.5) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 62% 18%, rgba(167,139,250,0.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 78% 82%, rgba(129,140,248,0.4) 0%, transparent 100%),
                radial-gradient(1px 1px at 92% 60%, rgba(167,139,250,0.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 48% 78%, rgba(99,102,241,0.5) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 22% 48%, rgba(139,92,246,0.6) 0%, transparent 100%);
            animation: twinkle 4s ease-in-out infinite alternate;
        }

        @keyframes twinkle {
            0%   { opacity: 0.6; }
            100% { opacity: 1; }
        }

        /* ── Nebula glow ── */
        .nebula {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }
        .nebula-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
            top: -100px; left: -100px;
            animation: drift 12s ease-in-out infinite alternate;
        }
        .nebula-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%);
            bottom: -80px; right: -80px;
            animation: drift 15s ease-in-out infinite alternate-reverse;
        }
        .nebula-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: drift 18s ease-in-out infinite alternate;
        }

        @keyframes drift {
            0%   { transform: translate(0, 0); }
            100% { transform: translate(30px, 20px); }
        }

        /* ── Login card ── */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        .login-card {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow:
                0 0 0 1px rgba(99,102,241,0.1),
                0 25px 50px rgba(0,0,0,0.5),
                inset 0 1px 0 rgba(255,255,255,0.05);
        }

        /* ── Header ── */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 1rem;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f1f5f9;
            letter-spacing: -0.025em;
            margin-bottom: 0.4rem;
        }

        .login-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 400;
        }

        /* ── Form ── */
        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid #334155;
            border-radius: 0.75rem;
            color: #e2e8f0;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        input::placeholder { color: #475569; }

        .error {
            color: #fca5a5;
            font-size: 0.8rem;
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .forgot-link {
            display: block;
            text-align: right;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #6366f1;
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: #818cf8; }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #4f46e5, #6d28d9);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
            letter-spacing: 0.01em;
        }

        .btn-submit:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* ── Divider ── */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #334155, transparent);
            margin: 1.75rem 0;
        }

        .footer-text {
            text-align: center;
            font-size: 0.78rem;
            color: #475569;
        }
    </style>
</head>
<body>

    <!-- Galaxy background -->
    <div class="stars"></div>
    <div class="nebula nebula-1"></div>
    <div class="nebula nebula-2"></div>
    <div class="nebula nebula-3"></div>

    <div class="login-wrapper">
        <div class="login-card">

            <div class="login-header">
                <div class="login-logo">🎓</div>
                <div class="login-title">Plateforme TP</div>
                <div class="login-subtitle">Connectez-vous à votre espace</div>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email"
                           placeholder="vous@exemple.com"
                           required autofocus value="{{ old('email') }}">
                    @error('email')
                        <div class="error">⚠ {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required>
                    @error('password')
                        <div class="error">⚠ {{ $message }}</div>
                    @enderror
                    <a href="{{ route('password.forgot') }}" class="forgot-link">
                        Mot de passe oublié ?
                    </a>
                </div>

                <button type="submit" class="btn-submit">
                    Se connecter →
                </button>
            </form>

            <div class="divider"></div>

            <div class="footer-text">
                Plateforme de Gestion des Travaux Pratiques
            </div>

        </div>
    </div>

</body>
</html>