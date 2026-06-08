<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurer votre mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
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
        .icon svg {
            width: 64px;
            height: 64px;
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
            font-size: 1rem;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #007bff;
        }
        .error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .requirements {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .requirements-title {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-weight: bold;
            margin-bottom: 0.4rem;
        }
        .requirements-title svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }
        .requirements ul {
            margin: 0.5rem 0 0 1.5rem;
            padding: 0;
        }
        .requirements li {
            margin: 0.25rem 0;
        }
        .btn {
            width: 100%;
            padding: 1rem;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn svg {
            width: 18px;
            height: 18px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s;
        }
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <!-- Lock icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#007bff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                <circle cx="12" cy="16" r="1" fill="#007bff"/>
            </svg>
        </div>
        <h1>Configurez votre mot de passe</h1>
        <p class="subtitle">Créez un mot de passe sécurisé pour votre compte</p>

        <div class="requirements">
            <div class="requirements-title">
                <!-- Shield icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#007bff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Exigences du mot de passe:
            </div>
            <ul>Min. 8 caractères, 1 majuscule, 1 chiffre, 1 caractère spécial</ul>
        </div>

        <form method="POST" action="{{ route('password.setup.submit') }}" autocomplete="off">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="{{ $email }}" disabled autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       autocomplete="new-password"
                       oninput="checkPasswordStrength(this.value)">
                <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required
                       autocomplete="new-password">
            </div>

            <button type="submit" class="btn">
                <!-- Checkmark icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Configurer mon compte
            </button>
        </form>
    </div>

    <script>
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('strengthBar');
            let strength = 0;

            // Check length
            if (password.length >= 8) strength++;
            
            // Check for lowercase
            if (/[a-z]/.test(password)) strength++;
            
            // Check for uppercase
            if (/[A-Z]/.test(password)) strength++;
            
            // Check for numbers
            if (/\d/.test(password)) strength++;
            
            // Check for special characters
            if (/[\W_]/.test(password)) strength++;

            // Update bar
            strengthBar.className = 'password-strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        }
    </script>
</body>
</html>