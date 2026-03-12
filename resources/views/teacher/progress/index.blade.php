<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Étudiants</title>
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
        .class-section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .class-section h2 {
            color: #007bff;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .students-table {
            width: 100%;
            border-collapse: collapse;
        }
        .students-table th,
        .students-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .students-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
        }
        .students-table tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Suivi de la Progression des Étudiants</h1>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                ← Retour
            </a>
        </div>

        @forelse($classes as $class)
            <div class="class-section">
                <h2>{{ $class->name }}</h2>
                
                @if($class->students->count() > 0)
                    <table class="students-table">
                        <thead>
                            <tr>
                                <th>Nom de l'étudiant</th>
                                <th>Email</th>
                                <th>Nombre d'étudiants</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $class->students->count() }}</td>
                                    <td>
                                        <a href="{{ route('teacher.progress.show', $student->id) }}" 
                                           class="btn btn-info btn-small">
                                            👁️ Voir détails
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color: #999; text-align: center; padding: 2rem;">
                        Aucun étudiant dans cette classe
                    </p>
                @endif
            </div>
        @empty
            <div class="class-section" style="text-align: center;">
                <p style="color: #999;">Vous n'avez aucune classe assignée</p>
            </div>
        @endforelse
    </div>
</body>
</html>