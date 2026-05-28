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
        .header-icon {
            display: inline-block;
            margin-bottom: 0.75rem;
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
            display: flex;
            align-items: center;
            gap: 0.4rem;
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
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
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
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .important {
            background: #ffebee;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .icon {
            flex-shrink: 0;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="header-icon">
                <!-- Graduation cap icon -->
                <svg class="icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
            <h1>Bienvenue sur la {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</h1>
        </div>

        <div class="content">
            <h2>Bonjour {{ $userName }},</h2>

            <p>Un compte a été créé pour vous sur {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}.</p>

            <div class="role-badge role-{{ $userRole }}">
                <!-- User icon -->
                <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Rôle: {{ $userRole === 'student' ? 'Étudiant' : 'Enseignant' }}
            </div>

            <div class="important">
                <!-- Shield icon -->
                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <div>
                    <strong>Important — Sécurité de votre compte</strong>
                    <p style="margin: 0.5rem 0 0 0;">
                        Pour des raisons de sécurité, vous devez créer votre propre mot de passe avant de pouvoir utiliser votre compte.
                    </p>
                </div>
            </div>

            <div class="credentials-box">
                <h3 style="margin-top: 0;">Vos informations de connexion temporaires</h3>

                <div class="credential-item">
                    <span class="credential-label">
                        <!-- Mail icon -->
                        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                        Email:
                    </span>
                    <span class="credential-value">{{ $userEmail }}</span>
                </div>

                <div class="credential-item">
                    <span class="credential-label">
                        <!-- Key icon -->
                        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="7.5" cy="15.5" r="5.5"/>
                            <path d="m21 2-9.6 9.6"/>
                            <path d="m15.5 7.5 3 3L22 7l-3-3"/>
                        </svg>
                        Mot de passe temporaire:
                    </span>
                    <span class="credential-value">{{ $temporaryPassword }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $setupUrl }}" class="btn">
                    <!-- Arrow right icon -->
                    <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8l4 4-4 4"/>
                        <path d="M8 12h8"/>
                    </svg>
                    Configurer mon compte maintenant
                </a>
            </div>

            <div class="warning">
                <!-- Clock icon -->
                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#856404" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span><strong>Ce lien expire dans 24 heures.</strong> Veuillez configurer votre compte dès que possible.</span>
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