<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Classe</title>
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
        .info-row {
            display: flex;
            margin-bottom: 1rem;
            padding: 0.5rem;
        }
        .info-label {
            font-weight: bold;
            min-width: 200px;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .join-code {
            font-family: monospace;
            background: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background-color: #007bff;
            color: white;
        }
        th, td {
            padding: 1rem;
            text-align: left;
        }
        tbody tr {
            border-bottom: 1px solid #ddd;
        }
        tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 {{ $class->name }}</h1>
            <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                ← Retour
            </a>
        </div>

        <div class="section">
            <h2>Informations de la Classe</h2>
            
            <div class="info-row">
                <div class="info-label">Nom:</div>
                <div class="info-value">{{ $class->name }}</div>
            </div>

            @if($class->description)
                <div class="info-row">
                    <div class="info-label">Description:</div>
                    <div class="info-value">{{ $class->description }}</div>
                </div>
            @endif

            <div class="info-row">
                <div class="info-label">Enseignant:</div>
                <div class="info-value">{{ $class->teacher ? $class->teacher->name : 'Non assigné' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Code d'accès:</div>
                <div class="info-value"><span class="join-code">{{ $class->join_code }}</span></div>
            </div>

            <div class="info-row">
                <div class="info-label">Statut:</div>
                <div class="info-value">
                    <span style="padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; background: {{ $class->status === 'active' ? '#d4edda' : '#f8d7da' }}; color: {{ $class->status === 'active' ? '#155724' : '#721c24' }};">
                        {{ $class->status === 'active' ? 'Actif' : 'Archivé' }}
                    </span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Nombre d'étudiants:</div>
                <div class="info-value">{{ $class->students->count() }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Date de création:</div>
                <div class="info-value">{{ $class->created_at->format('d/m/Y à H:i') }}</div>
            </div>
        </div>

        <div class="section">
            <h2>👥 Étudiants Inscrits ({{ $class->students->count() }})</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($class->students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->pivot->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: #999;">
                                Aucun étudiant inscrit
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>