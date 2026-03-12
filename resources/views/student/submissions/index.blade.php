<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Soumissions</title>
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
            <h1>📊 Mes Soumissions</h1>
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                ← Retour
            </a>
        </div>

        <div class="submissions-table">
            <table>
                <thead>
                    <tr>
                        <th>TP</th>
                        <th>Enseignant</th>
                        <th>Date de soumission</th>
                        <th>Statut</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr>
                            <td>{{ $submission->tp->title }}</td>
                            <td>{{ $submission->tp->teacher->name }}</td>
                            <td>{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $submission->status }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $submission->grade ? $submission->grade . '/20' : 'En attente' }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('student.tps.show', $submission->tp_id) }}" 
                                   class="btn btn-info btn-small">
                                    👁️ Voir détails
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #999;">
                                Vous n'avez soumis aucun TP pour le moment
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>