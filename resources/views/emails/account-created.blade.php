<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurez votre compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        .content {
            padding: 2rem;
        }
        .content h2 {
            color: #333;
            margin-top: 0;
        }
        .credentials-box {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 4px;
        }
        .credential-item {
            margin: 1rem 0;
        }
        .credential-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 0.3rem;
        }
        .credential-value {
            font-size: 1.1rem;
            color: #333;
            background: white;
            padding: 0.5rem;
            border-radius: 4px;
            display: inline-block;
            font-family: monospace;
        }
        .role-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            margin: 1rem 0;
        }
        .role-student {
            background: #e3f2fd;
            color: #1976d2;
        }
        .role-teacher {
            background: #fff3e0;
            color: #f57c00;
        }
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 1.5rem 0;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .btn:hover {
            background: #0056b3;
        }
        .footer {
            background: #f8f9fa;
            padding: 1.5rem;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }
        .important {
            background: #ffebee;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎓 Bienvenue sur la {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</h1>
        </div>

        <div class="content">
            <h2>Bonjour {{ $userName }},</h2>
            
            <p>Un compte a été créé pour vous sur {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}.</p>

            <div class="role-badge role-{{ $userRole }}">
                Rôle: {{ $userRole === 'student' ? 'Étudiant' : 'Enseignant' }}
            </div>

            <div class="important">
                <strong>🔐 Important - Sécurité de votre compte</strong>
                <p style="margin: 0.5rem 0 0 0;">
                    Pour des raisons de sécurité, vous devez créer votre propre mot de passe avant de pouvoir utiliser votre compte.
                </p>
            </div>

            <div class="credentials-box">
                <h3 style="margin-top: 0;">Vos informations de connexion temporaires</h3>
                
                <div class="credential-item">
                    <span class="credential-label">📧 Email:</span>
                    <span class="credential-value">{{ $userEmail }}</span>
                </div>

                <div class="credential-item">
                    <span class="credential-label">🔑 Mot de passe temporaire:</span>
                    <span class="credential-value">{{ $temporaryPassword }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $setupUrl }}" class="btn">
                    🚀 Configurer mon compte maintenant
                </a>
            </div>

            <div class="warning">
                ⏰ <strong>Ce lien expire dans 24 heures.</strong> Veuillez configurer votre compte dès que possible.
            </div>

            <h3>Prochaines étapes:</h3>
            <ol style="line-height: 2;">
                <li>Cliquez sur le bouton ci-dessus</li>
                <li>Connectez-vous avec votre mot de passe temporaire</li>
                <li>Créez un nouveau mot de passe sécurisé</li>
                <li>Commencez à utiliser la plateforme!</li>
            </ol>
        </div>

        <div class="footer">
            <p>Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet email.</p>
            <p>© {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }} - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>