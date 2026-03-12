<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noter la soumission</title>
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
        .submission-content {
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
        input, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: Arial, sans-serif;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
        }
        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn-primary {
            background-color: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            flex: 1;
            text-align: center;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Noter la soumission</h1>
            <a href="{{ route('teacher.tps.show', $submission->tp_id) }}" class="btn btn-secondary">
                ← Retour au TP
            </a>
        </div>

        <div class="info-card">
            <h2>Informations</h2>
            
            <div class="info-row">
                <div class="info-label">TP:</div>
                <div class="info-value">{{ $submission->tp->title }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Étudiant:</div>
                <div class="info-value">{{ $submission->student->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Date de soumission:</div>
                <div class="info-value">{{ $submission->submitted_at->format('d/m/Y à H:i') }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Statut actuel:</div>
                <div class="info-value">{{ ucfirst($submission->status) }}</div>
            </div>

            @if($submission->grade)
                <div class="info-row">
                    <div class="info-label">Note actuelle:</div>
                    <div class="info-value">{{ $submission->grade }}/20</div>
                </div>
            @endif

            <h3 style="margin-top: 1.5rem; margin-bottom: 0.5rem;">Contenu de la soumission:</h3>
            <div class="submission-content">{{ $submission->content }}</div>
        </div>

        <div class="form-container">
            <h2 style="margin-bottom: 1.5rem;">{{ $submission->grade ? 'Modifier' : 'Attribuer' }} la note</h2>
            
            <form method="POST" action="{{ route('teacher.submissions.grade', [$submission->tp_id, $submission->id]) }}">
                @csrf

                <div class="form-group">
                    <label for="grade">Note (sur 20) *</label>
                    <input type="number" 
                           id="grade" 
                           name="grade" 
                           step="0.01"
                           min="0"
                           max="20"
                           value="{{ old('grade', $submission->grade) }}" 
                           required>
                    @error('grade')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="teacher_comment">Commentaire</label>
                    <textarea id="teacher_comment" 
                              name="teacher_comment" 
                              placeholder="Commentaires pour l'étudiant...">{{ old('teacher_comment', $submission->teacher_comment) }}</textarea>
                    @error('teacher_comment')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        ✓ Enregistrer la note
                    </button>
                    <a href="{{ route('teacher.tps.show', $submission->tp_id) }}" class="btn btn-secondary">
                        ✗ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>