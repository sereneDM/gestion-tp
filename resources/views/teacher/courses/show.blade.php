<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->name }}</title>
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
        .grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .section {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section h2 {
            color: #007bff;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .join-code-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .join-code-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        .join-code {
            font-size: 2.5rem;
            font-weight: bold;
            font-family: monospace;
            letter-spacing: 0.1em;
            margin: 1rem 0;
        }
        .copy-btn {
            background: white;
            color: #667eea;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 1rem;
        }
        .copy-btn:hover {
            background: #f0f0f0;
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
        .students-table {
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
        @media (max-width: 768px) {
            .grid-2col {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 {{ $course->name }}</h1>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('teacher.courses.edit', $course->id) }}" class="btn btn-warning">
                    ✏️ Modifier
                </a>
                <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary">
                    ← Retour
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="join-code-box">
            <div class="join-code-label">Code d'accès au cours</div>
            <div class="join-code" id="joinCode">{{ $course->join_code }}</div>
            <button class="copy-btn" onclick="copyJoinCode()">
                📋 Copier le code
            </button>
            <div style="margin-top: 1rem; font-size: 0.9rem; opacity: 0.9;">
                Partagez ce code avec vos étudiants pour qu'ils rejoignent le cours
            </div>
            <form method="POST" action="{{ route('teacher.courses.regenerate-code', $course->id) }}" style="margin-top: 1rem;">
                @csrf
                <button type="submit" 
                        class="copy-btn" 
                        onclick="return confirm('Générer un nouveau code? L\'ancien code ne fonctionnera plus.')"
                        style="background: rgba(255,255,255,0.2); color: white;">
                    🔄 Générer un nouveau code
                </button>
            </form>
        </div>

        <div class="grid-2col">
            <div class="section">
                <h2>Informations du Cours</h2>
                
                <div class="info-row">
                    <div class="info-label">Nom:</div>
                    <div class="info-value">{{ $course->name }}</div>
                </div>

                @if($course->description)
                    <div class="info-row">
                        <div class="info-label">Description:</div>
                        <div class="info-value">{{ $course->description }}</div>
                    </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Statut:</div>
                    <div class="info-value">
                        <span style="display: inline-block; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: bold; background: {{ $course->status === 'active' ? '#d4edda' : '#f8d7da' }}; color: {{ $course->status === 'active' ? '#155724' : '#721c24' }};">
                            {{ $course->status === 'active' ? 'Actif' : 'Archivé' }}
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Nombre d'étudiants:</div>
                    <div class="info-value">{{ $course->students->count() }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Date de création:</div>
                    <div class="info-value">{{ $course->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="section">
                <h2>Actions Rapides</h2>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <a href="{{ route('teacher.tps.create') }}?class_id={{ $course->id }}" 
                       class="btn" 
                       style="background: #28a745; color: white; text-align: center;">
                        ➕ Créer un TP pour ce cours
                    </a>
                    <a href="{{ route('teacher.courses.edit', $course->id) }}" 
                       class="btn btn-warning" 
                       style="text-align: center;">
                        ✏️ Modifier le cours
                    </a>
                    <form method="POST" 
                          action="{{ route('teacher.courses.destroy', $course->id) }}"
                          onsubmit="return confirm('Supprimer ce cours? Cette action est irréversible!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger" 
                                style="width: 100%;">
                            🗑️ Supprimer le cours
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="students-table">
            <h2 style="padding: 1.5rem; background-color: #f8f9fa; margin: 0;">
                👥 Étudiants Inscrits ({{ $course->students->count() }})
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($course->students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form method="POST" 
                                      action="{{ route('teacher.courses.remove-student', [$course->id, $student->id]) }}"
                                      onsubmit="return confirm('Retirer cet étudiant du cours?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">
                                        ✗ Retirer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: #999;">
                                Aucun étudiant inscrit pour le moment
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function copyJoinCode() {
            const code = document.getElementById('joinCode').textContent;
            navigator.clipboard.writeText(code).then(() => {
                alert('Code copié: ' + code);
            });
        }
    </script>
</body>
</html>