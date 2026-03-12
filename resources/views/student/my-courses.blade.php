<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours</title>
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
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        .course-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            border-left: 4px solid #28a745;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .course-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .course-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        .course-description {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .course-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: #666;
        }
        .course-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Mes Cours</h1>
            <div class="header-buttons">
                <a href="{{ route('student.join-course.form') }}" class="btn btn-primary">
                    ➕ Rejoindre un cours
                </a>
                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                    ← Retour
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                ✗ {{ session('error') }}
            </div>
        @endif

        @if($courses->count() > 0)
            <div class="courses-grid">
                @foreach($courses as $course)
                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-name">{{ $course->name }}</div>
                        </div>

                        @if($course->description)
                            <div class="course-description">{{ $course->description }}</div>
                        @endif

                        <div class="course-meta">
                            <div class="course-meta-item">
                                👨‍🏫 <strong>Enseignant:</strong> {{ $course->teacher->name }}
                            </div>
                            <div class="course-meta-item">
                                👥 <strong>Étudiants:</strong> {{ $course->students_count }}
                            </div>
                            <div class="course-meta-item">
                                📅 <strong>Inscrit le:</strong> {{ $course->pivot->created_at->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="action-buttons">
                            <form method="POST" 
                                  action="{{ route('student.leave-course', $course->id) }}"
                                  onsubmit="return confirm('Voulez-vous vraiment quitter ce cours?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small">
                                    ✗ Quitter le cours
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📚</div>
                <h2>Aucun cours rejoint</h2>
                <p style="color: #666; margin: 1rem 0;">Demandez un code d'accès à votre enseignant pour rejoindre un cours!</p>
                <a href="{{ route('student.join-course.form') }}" class="btn btn-primary">
                    ➕ Rejoindre mon premier cours
                </a>
            </div>
        @endif
    </div>
</body>
</html>