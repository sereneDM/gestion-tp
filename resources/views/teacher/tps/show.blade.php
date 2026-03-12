<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du TP</title>
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
        .btn-info {
            background-color: #17a2b8;
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
        .info-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-card h2 {
            color: #007bff;
            margin-bottom: 1rem;
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
            min-width: 150px;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        .submissions-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
            display: inline-block;
        }
        .status-submitted {
            background-color: #ffc107;
            color: #333;
        }
        .status-graded {
            background-color: #28a745;
            color: white;
        }
        .status-late {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 {{ $tp->title }}</h1>
            <a href="{{ route('teacher.tps.index') }}" class="btn btn-secondary">
                ← Retour à la liste
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="info-card">
            <h2>Informations du TP</h2>
            
            <div class="info-row">
                <div class="info-label">Titre:</div>
                <div class="info-value">{{ $tp->title }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Description:</div>
                <div class="info-value">{{ $tp->description }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Classe:</div>
                <div class="info-value">{{ $tp->class ? $tp->class->name : 'Toutes les classes' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Date d'échéance:</div>
                <div class="info-value">{{ $tp->due_date ? $tp->due_date->format('d/m/Y') : 'Non définie' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Statut:</div>
                <div class="info-value">{{ ucfirst($tp->status) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Nombre de soumissions:</div>
                <div class="info-value">{{ $tp->submissions->count() }}</div>
            </div>
        </div>

        <div class="submissions-table">
            <h2 style="padding: 1.5rem; background-color: #f8f9fa; margin: 0;">
                Soumissions des étudiants
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Date de soumission</th>
                        <th>Statut</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tp->submissions as $submission)
                        <tr>
                            <td>{{ $submission->student->name }}</td>
                            <td>{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $submission->status }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $submission->grade ? $submission->grade . '/20' : 'Non noté' }}
                            </td>
                            <td>
                                <a href="{{ route('teacher.submissions.show', [$tp->id, $submission->id]) }}" 
                                   class="btn btn-info btn-small">
                                    👁️ Voir / Noter
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: #999;">
                                Aucune soumission pour le moment
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>