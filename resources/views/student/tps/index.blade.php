<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP Disponibles</title>
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
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-small {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
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
        .tp-card.submitted {
            border: 2px solid #28a745;
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
        .badge-submitted {
            background-color: #28a745;
            color: white;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Travaux Pratiques Disponibles</h1>
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                ← Retour
            </a>
        </div>

        @if($tps->count() > 0)
            <div class="tps-grid">
                @foreach($tps as $tp)
                    <div class="tp-card {{ in_array($tp->id, $submittedTpIds) ? 'submitted' : '' }}">
                        <div class="tp-title">{{ $tp->title }}</div>
                        
                        @if(in_array($tp->id, $submittedTpIds))
                            <span class="status-badge badge-submitted">
                                ✓ Soumis
                            </span>
                        @else
                            <span class="status-badge badge-pending">
                                À faire
                            </span>
                        @endif

                        <div class="tp-description">{{ $tp->description }}</div>

                        <div class="tp-meta">
                            👨‍🏫 Enseignant: {{ $tp->teacher->name }}
                        </div>
                        <div class="tp-meta">
                            📅 Échéance: {{ $tp->due_date ? $tp->due_date->format('d/m/Y') : 'Non définie' }}
                        </div>
                        <div class="tp-meta">
                            👥 Classe: {{ $tp->class ? $tp->class->name : 'Toutes les classes' }}
                        </div>

                        <div style="margin-top: 1rem;">
                            <a href="{{ route('student.tps.show', $tp->id) }}" 
                               class="btn {{ in_array($tp->id, $submittedTpIds) ? 'btn-success' : 'btn-primary' }} btn-small"
                               style="width: 100%;">
                                {{ in_array($tp->id, $submittedTpIds) ? '👁️ Voir ma soumission' : '✏️ Voir et soumettre' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                <h2>Aucun TP disponible</h2>
                <p>Aucun travail pratique n'est actuellement publié</p>
            </div>
        @endif
    </div>
</body>
</html>