<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejoindre un Cours</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .icon {
            text-align: center;
            font-size: 4rem;
            margin-bottom: 1rem;
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
        input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 1.2rem;
            font-family: monospace;
            text-align: center;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
        input:focus {
            outline: none;
            border-color: #007bff;
        }
        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            text-align: center;
        }
        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            padding: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            display: inline-block;
            flex: 1;
            text-align: center;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .example {
            text-align: center;
            color: #999;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🎓</div>
        <h1>Rejoindre un Cours</h1>
        <p class="subtitle">Entrez le code fourni par votre enseignant</p>

        <div class="info-box">
            ℹ️ Demandez à votre enseignant le code d'accès du cours pour vous inscrire.
        </div>

        <form method="POST" action="{{ route('student.join-course') }}">
            @csrf
            
            <div class="form-group">
                <label for="join_code">Code du Cours</label>
                <input type="text" 
                       id="join_code" 
                       name="join_code" 
                       placeholder="ABC-XYZ-123"
                       maxlength="15"
                       value="{{ old('join_code') }}"
                       required 
                       autofocus>
                <div class="example">Format: XXX-XXX-123</div>
                @error('join_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    ✓ Rejoindre le Cours
                </button>
                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
                    ✗ Annuler
                </a>
            </div>
        </form>
    </div>

    <script>
        // Auto format join code as user types
        document.getElementById('join_code').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            if (value.length > 9) {
                value = value.substring(0, 9);
            }
            // Format as XXX-XXX-XXX
            if (value.length > 6) {
                value = value.substring(0, 3) + '-' + value.substring(3, 6) + '-' + value.substring(6);
            } else if (value.length > 3) {
                value = value.substring(0, 3) + '-' + value.substring(3);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>