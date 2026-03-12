<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs Système</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: #333;
        }
        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-block;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section h2 {
            color: #007bff;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        .info-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 0.5rem;
        }
        .info-value {
            color: #333;
            font-size: 1.1rem;
        }
        .activity-item {
            padding: 1rem;
            border-left: 4px solid #007bff;
            background: #f8f9fa;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .activity-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .activity-action {
            font-weight: bold;
            color: #333;
        }
        .activity-time {
            color: #999;
            font-size: 0.9rem;
        }
        .activity-user {
            color: #007bff;
            font-size: 0.9rem;
        }
        .activity-details {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Logs et Supervision Système</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                ← Retour
            </a>
        </div>

        <!-- System Information -->
        <div class="section">
            <h2>ℹ️ Informations Système</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Version PHP</div>
                    <div class="info-value">{{ $systemInfo['php_version'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Version Laravel</div>
                    <div class="info-value">{{ $systemInfo['laravel_version'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Base de données</div>
                    <div class="info-value">{{ ucfirst($systemInfo['database']) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Environnement</div>
                    <div class="info-value">{{ ucfirst($systemInfo['environment']) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Mode Debug</div>
                    <div class="info-value">{{ $systemInfo['debug_mode'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fuseau horaire</div>
                    <div class="info-value">{{ $systemInfo['timezone'] }}</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="section">
            <h2>🕐 Activité Récente</h2>
            @foreach($activities as $activity)
                <div class="activity-item">
                    <div class="activity-header">
                        <div class="activity-action">{{ $activity['action'] }}</div>
                        <div class="activity-time">{{ $activity['timestamp']->diffForHumans() }}</div>
                    </div>
                    <div class="activity-user">👤 {{ $activity['user'] }}</div>
                    <div class="activity-details">{{ $activity['details'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="section">
            <h2>⚠️ Note</h2>
            <p style="color: #666;">
                Pour un système de logs plus avancé avec suivi complet des activités, 
                il est recommandé d'utiliser un package comme <strong>spatie/laravel-activitylog</strong> 
                pour enregistrer automatiquement toutes les actions des utilisateurs.
            </p>
        </div>
    </div>
</body>
</html>