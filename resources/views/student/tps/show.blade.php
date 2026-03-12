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
            max-width: 900px;
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
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
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
        .description-box {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 4px;
            margin-top: 1rem;
            white-space: pre-wrap;
        }
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: bold;
        }
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: Arial, sans-serif;
            min-height: 200px;
            resize: vertical;
        }
        textarea:focus {
            outline: none;
            border-color: #007bff;
        }
        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .submission-box {
            background: #e8f5e9;
            padding: 2rem;
            border-radius: 8px;
            border: 2px solid #28a745;
        }
        .grade-box {
            background: #fff3cd;
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }
        .grade-number {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 {{ $tp->title }}</h1>
            <a href="{{ route('student.tps.index') }}" class="btn btn-secondary">
                ← Retour à la liste
            </a>
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

        <div class="info-card">
            <h2>Informations du TP</h2>
            
            <div class="info-row">
                <div class="info-label">Titre:</div>
                <div class="info-value">{{ $tp->title }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Enseignant:</div>
                <div class="info-value">{{ $tp->teacher->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Classe:</div>
                <div class="info-value">{{ $tp->class ? $tp->class->name : 'Toutes les classes' }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Date d'échéance:</div>
                <div class="info-value">
                    {{ $tp->due_date ? $tp->due_date->format('d/m/Y') : 'Non définie' }}
                    @if($tp->due_date && now()->isAfter($tp->due_date))
                        <span style="color: #dc3545; font-weight: bold;"> (Échue)</span>
                    @endif
                </div>
            </div>

            <h3 style="margin-top: 1.5rem; margin-bottom: 0.5rem;">Description / Énoncé:</h3>
            <div class="description-box">{{ $tp->description }}</div>
        </div>

        @if($submission)
            <!-- Student has already submitted -->
            <div class="submission-box">
                <h2 style="color: #28a745; margin-bottom: 1rem;">✓ Vous avez soumis ce TP</h2>
                
                <div class="info-row">
                    <div class="info-label">Date de soumission:</div>
                    <div class="info-value">{{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Statut:</div>
                    <div class="info-value">{{ ucfirst($submission->status) }}</div>
                </div>

                @if($submission->grade)
                    <div class="grade-box">
                        <strong>Note:</strong>
                        <div class="grade-number">{{ $submission->grade }}/20</div>
                        
                        @if($submission->teacher_comment)
                            <div style="margin-top: 1rem;">
                                <strong>Commentaire de l'enseignant:</strong>
                                <div style="margin-top: 0.5rem; white-space: pre-wrap;">{{ $submission->teacher_comment }}</div>
                            </div>
                        @endif
                    </div>
                @else
                    <p style="margin-top: 1rem; color: #666;">
                        En attente de correction par l'enseignant
                    </p>
                @endif

                <h3 style="margin-top: 1.5rem; margin-bottom: 0.5rem;">Votre travail soumis:</h3>
                <div class="description-box">{{ $submission->content }}</div>
            </div>
        @else
            <!-- Submission form -->
            <div class="form-container">
                <h2 style="margin-bottom: 1.5rem;">✏️ Soumettre votre travail</h2>
                
                <form method="POST" action="{{ route('student.tps.submit', $tp->id) }}">
                    @csrf

                    <div class="form-group">
                        <label for="content">Votre travail *</label>
                        <textarea id="content" 
                                  name="content" 
                                  placeholder="Rédigez votre compte rendu ici..."
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        ✓ Soumettre mon travail
                    </button>
                </form>
            </div>
        @endif
    </div>
</body>
</html>