<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h1 { text-align: center; color: #333; font-size: 1.4rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        h1 svg { width: 22px; height: 22px; flex-shrink: 0; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #555; }
        input { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-size: 1rem; }
        input:focus { outline: none; border-color: #007bff; }
        button { width: 100%; padding: 0.75rem; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; margin-top: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        button svg { width: 17px; height: 17px; flex-shrink: 0; }
        button:hover { background: #218838; }
        .error { color: red; font-size: 0.875rem; margin-top: 0.5rem; }
        .hint { font-size: 0.8rem; color: #999; margin-top: 0.4rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <!-- Key icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="7.5" cy="15.5" r="5.5"/>
                <path d="M21 2l-9.6 9.6"/>
                <path d="M15.5 7.5l3 3L22 7l-3-3"/>
            </svg>
            Nouveau mot de passe
        </h1>
        <form method="POST" action="{{ route('password.update') }}" autocomplete="off">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <div class="hint">Min. 8 caractères, 1 majuscule, 1 chiffre, 1 caractère spécial</div>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>
            <button type="submit">
                <!-- Checkmark icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Réinitialiser mon mot de passe
            </button>
        </form>
    </div>
</body>
</html>