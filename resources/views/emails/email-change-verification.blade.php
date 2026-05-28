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
        .header-icon { display: inline-block; margin-bottom: 0.75rem; }
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
            display: flex; align-items: flex-start; gap: 0.5rem;
        }
        .footer {
            background: #f8f9fa; padding: 1.5rem;
            text-align: center; color: #666; font-size: 0.9rem;
        }
        .icon { flex-shrink: 0; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-icon">
                <!-- Lock icon -->
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h1>Confirmation de changement d'email</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $userName }}</strong>,</p>
            <p>Vous avez demandé à changer votre adresse email vers <strong>{{ $newEmail }}</strong>.</p>
            <p>Voici votre code de confirmation :</p>
            <div class="code-box">
                <div class="code">{{ $code }}</div>
            </div>
            <div class="warning">
                <!-- Clock icon -->
                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#856404" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span>
                    <strong>Ce code expire dans 15 minutes.</strong>
                    Si vous n'avez pas demandé ce changement, ignorez cet email.
                </span>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</p>
        </div>
    </div>
</body>
</html>