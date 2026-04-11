<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h1 { text-align: center; color: #333; font-size: 1.4rem; margin-bottom: 0.5rem; }
        p { color: #666; text-align: center; font-size: 0.9rem; margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; color: #555; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 1rem; }
        input:focus { outline: none; border-color: #007bff; }
        button { width: 100%; padding: 0.75rem; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; margin-top: 1rem; }
        button:hover { background: #0056b3; }
        .error { color: red; font-size: 0.875rem; margin-top: 0.5rem; }
        .success { background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; text-align: center; }
        .back-link { display: block; text-align: center; margin-top: 1rem; color: #007bff; text-decoration: none; font-size: 0.9rem; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Mot de passe oublié</h1>
        <p>Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

        @if(session('success'))
            <div class="success">✓ {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div>
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">📧 Envoyer le lien</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">← Retour à la connexion</a>
    </div>
</body>
</html>