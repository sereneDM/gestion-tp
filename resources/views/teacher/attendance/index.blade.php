<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la Présence</title>
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
            max-width: 800px;
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
            font-size: 1rem;
            width: 100%;
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
        select, input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        select:focus, input:focus {
            outline: none;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Gestion de la Présence</h1>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
                ← Retour
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="form-container">
            <h2 style="margin-bottom: 1.5rem;">Prendre les présences</h2>
            
            <form method="GET" action="{{ route('teacher.attendance.show') }}">
                <div class="form-group">
                    <label for="class_id">Sélectionner une classe *</label>
                    <select id="class_id" name="class_id" required>
                        <option value="">-- Choisir une classe --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">
                                {{ $class->name }} ({{ $class->students->count() }} étudiants)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Date *</label>
                    <input type="date" 
                           id="date" 
                           name="date" 
                           value="{{ date('Y-m-d') }}" 
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Continuer →
                </button>
            </form>
        </div>
    </div>
</body>
</html>