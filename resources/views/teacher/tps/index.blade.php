<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes TP</title>
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
            max-width: 1400px;
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
        .header-buttons {
            display: flex;
            gap: 1rem;
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
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-small {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .tps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        .tp-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .tp-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .tp-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 0.5rem;
        }
        .tp-description {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .tp-meta {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 0.5rem;
        }
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .status-draft {
            background-color: #ffc107;
            color: #333;
        }
        .status-published {
            background-color: #28a745;
            color: white;
        }
        .status-closed {
            background-color: #dc3545;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .delete-form {
            display: inline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Mes Travaux Pratiques</h1>
            <div class="header-buttons">
                <a href="{{ route('teacher.tps.create') }}" class="btn btn-primary">
                    ➕ Créer un TP
                </a>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                    ← Retour
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($tps->count() > 0)
            <div class="tps-grid">
                @foreach($tps as $tp)
                    <div class="tp-card">
                        <div class="tp-title">{{ $tp->title }}</div>
                        
                        <span class="status-badge status-{{ $tp->status }}">
                            {{ ucfirst($tp->status) }}
                        </span>

                        <div class="tp-description">{{ $tp->description }}</div>

                        <div class="tp-meta">
                            📅 Échéance: {{ $tp->due_date ? $tp->due_date->format('d/m/Y') : 'Non définie' }}
                        </div>
                        <div class="tp-meta">
                            👥 Classe: {{ $tp->class ? $tp->class->name : 'Toutes les classes' }}
                        </div>
                        <div class="tp-meta">
                            📊 Soumissions: {{ $tp->submissions->count() }}
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('teacher.tps.show', $tp->id) }}" class="btn btn-info btn-small">
                                👁️ Voir détails
                            </a>
                            <a href="{{ route('teacher.tps.edit', $tp->id) }}" class="btn btn-warning btn-small">
                                ✏️ Modifier
                            </a>
                            <form method="POST" 
                                  action="{{ route('teacher.tps.destroy', $tp->id) }}"
                                  class="delete-form"
                                  onsubmit="return confirm('Supprimer ce TP?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                <h2>Aucun TP créé</h2>
                <p>Cliquez sur "Créer un TP" pour commencer</p>
            </div>
        @endif
    </div>
</body>
</html>