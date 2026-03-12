<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur - Dashboard</title>
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
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .welcome-box {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .welcome-box h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        .welcome-box p {
            color: #666;
        }
        .button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .admin-button {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .admin-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border-color: #007bff;
        }
        .admin-button h3 {
            color: #007bff;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        .admin-button p {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Panneau d'Administration</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Déconnexion</button>
            </form>
        </div>


        <div class="button-grid">
            <a href="{{ route('admin.users.index') }}" class="admin-button">
                <h3>👥 Gérer les utilisateurs</h3>
                <p>Ajouter, modifier, supprimer des utilisateurs et gérer leurs rôles</p>
            </a>

            <a href="{{ route('admin.classes.index') }}" class="admin-button">
                <h3>📚 Gérer les classes</h3>
                <p>Organiser les groupes et classes</p>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="admin-button">
                <h3>⚙️ Paramètres système</h3>
                <p>Configuration générale de la plateforme</p>
            </a>

            <a href="{{ route('admin.statistics') }}" class="admin-button">
                <h3>📊 Statistiques globales</h3>
                <p>Vue d'ensemble et analyses du système</p>
            </a>

            <a href="{{ route('admin.system-logs') }}" class="admin-button">
                <h3>📋 Logs système</h3>
                <p>Supervision et monitoring de l'activité</p>
            </a>
        </div>
    </div>
</body>
</html>