<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de changement d'email</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container {
            max-width: 600px; margin: 20px auto; background: white;
            border-radius: 8px; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; padding: 2rem; text-align: center;
        }
        .header h1 { margin: 0; font-size: 1.6rem; }
        .content { padding: 2rem; }
        .code-box {
            background: #f8f9fa; border: 2px dashed #007bff;
            border-radius: 8px; text-align: center;
            padding: 2rem; margin: 1.5rem 0;
        }
        .code {
            font-size: 2.5rem; font-weight: bold;
            font-family: monospace; letter-spacing: 0.3em; color: #007bff;
        }
        .warning {
            background: #fff3cd; border-left: 4px solid #ffc107;
            padding: 1rem; border-radius: 4px; margin-top: 1rem;
        }
        .footer {
            background: #f8f9fa; padding: 1.5rem;
            text-align: center; color: #666; font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Confirmation de changement d'email</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $userName }}</strong>,</p>
            <p>Vous avez demandé à changer votre adresse email vers <strong>{{ $newEmail }}</strong>.</p>
            <p>Voici votre code de confirmation :</p>

            <div class="code-box">
                <div class="code">{{ $code }}</div>
            </div>

            <div class="warning">
                ⏰ <strong>Ce code expire dans 15 minutes.</strong>
                Si vous n'avez pas demandé ce changement, ignorez cet email.
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Plateforme de Gestion des TP</p>
        </div>
    </div>
</body>
</html>