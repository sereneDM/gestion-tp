<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 2rem; }
        .container { max-width: 500px; margin: 0 auto; background: white; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        p { color: #555; line-height: 1.6; }
        .btn {
            display: inline-block;
            margin: 1.5rem 0;
            padding: 0.75rem 2rem;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1rem;
        }
        .footer { margin-top: 2rem; font-size: 0.8rem; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Réinitialisation de mot de passe</h2>
        <p>Vous avez demandé une réinitialisation de votre mot de passe sur la Plateforme TP.</p>
        <p>Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe :</p>

        <a href="{{ $resetLink }}" class="btn">Réinitialiser mon mot de passe</a>

        <p>Ce lien expirera dans <strong>24 heures</strong>.</p>
        <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>

        <div class="footer">
            Plateforme de Gestion des TP
        </div>
    </div>
</body>
</html>