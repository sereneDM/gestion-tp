<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enseignant - Dashboard</title>
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
        }
        .welcome-box {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .action-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .action-card h3 {
            color: #007bff;
            margin-bottom: 1rem;
        }
        .action-card.highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .action-card.highlight h3 {
            color: white;
        }
        .action-card.highlight p {
            color: rgba(255,255,255,0.9);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Espace Enseignant</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Déconnexion</button>
            </form>
        </div>

        <div class="welcome-box">
            <h2>Bienvenue, {{ Auth::user()->name }}</h2>
            <p>Tableau de bord enseignant</p>
        </div>

        <div class="actions-grid">
            <a href="{{ route('teacher.courses.index') }}" class="action-card highlight">
                <h3>📚 Mes Cours</h3>
                <p>Gérer mes cours et codes d'accès</p>
            </a>

            <a href="{{ route('teacher.tps.index') }}" class="action-card">
                <h3>📝 Gestion des TP</h3>
                <p>Créer et gérer les travaux