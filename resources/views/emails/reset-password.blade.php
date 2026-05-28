<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de mot de passe</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 2rem; }
        .container {
            max-width: 500px; margin: 0 auto; background: white;
            border-radius: 8px; padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .title-row {
            display: flex; align-items: center; gap: 0.6rem;
            margin-bottom: 1rem;
        }
        h2 { color: #333; margin: 0; }
        p { color: #555; line-height: 1.6; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1.5rem 0;
            padding: 0.75rem 2rem;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1rem;
        }
        .footer { margin-top: 2rem; font-size: 0.8rem; color: #999; }
        .icon { flex-shrink: 0; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title-row">
            <!-- Lock icon -->
            <svg class="icon" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#007bff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <h2>Réinitialisation de mot de passe</h2>
        </div>
        <p>Vous avez demandé une réinitialisation de votre mot de passe sur la {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}.</p>
        <p>Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>
        <a href="{{ $resetLink }}" class="btn">
            <!-- Arrow right circle icon -->
            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8l4 4-4 4"/>
                <path d="M8 12h8"/>
            </svg>
            Réinitialiser mon mot de passe
        </a>
        <p>Ce lien expirera dans <strong>24 heures</strong>.</p>
        <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
        <div class="footer">
            {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}
        </div>
    </div>
</body>
</html>