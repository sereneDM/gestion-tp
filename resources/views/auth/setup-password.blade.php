<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurer votre mot de passe</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
      // Prevent flash - Load theme immediately
      const theme = localStorage.getItem('theme') || 'dark';
      if (theme === 'light') {
        document.documentElement.classList.remove('dark');
      } else {
        document.documentElement.classList.add('dark');
      }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }
        .container {
            @apply bg-white dark:bg-[#1e293b] rounded-lg shadow-lg p-12 w-full max-w-md;
        }
        h1 {
            @apply text-2xl font-bold text-center text-slate-900 dark:text-white mb-1;
        }
        .subtitle {
            @apply text-center text-slate-600 dark:text-slate-400 text-sm mb-6;
        }
        .icon {
            @apply text-center text-5xl mb-4;
        }
        .form-group {
            @apply mb-6;
        }
        label {
            @apply block mb-2 text-slate-700 dark:text-slate-200 font-semibold;
        }
        input {
            @apply w-full px-3 py-2 border-2 border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:border-violet-500 dark:focus:border-violet-400;
        }
        input:disabled {
            @apply opacity-60 cursor-not-allowed;
        }
        .error {
            @apply text-red-600 dark:text-red-400 text-sm mt-1;
        }
        .requirements {
            @apply bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-400 px-4 py-3 mb-6 rounded text-sm text-slate-700 dark:text-slate-300;
        }
        .requirements ul {
            @apply ml-5 mt-2 list-disc;
        }
        .requirements li {
            @apply my-1;
        }
        .btn {
            @apply w-full py-2 px-4 bg-violet-600 dark:bg-violet-600 hover:bg-violet-700 dark:hover:bg-violet-700 text-white font-semibold rounded-lg cursor-pointer transition-colors duration-200;
        }
        .password-strength {
            @apply mt-2 h-1 bg-slate-300 dark:bg-slate-600 rounded-full overflow-hidden;
        }
        .password-strength-bar {
            @apply h-full transition-all duration-300;
            width: 0%;
        }
        .strength-weak { @apply bg-red-500; width: 33%; }
        .strength-medium { @apply bg-yellow-500; width: 66%; }
        .strength-strong { @apply bg-green-500; width: 100%; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0f172a] min-h-screen flex items-center justify-center">
    <div class="container">
        <div class="icon">🔐</div>
        <h1>Configurez votre mot de passe</h1>
        <p class="subtitle">Créez un mot de passe sécurisé pour votre compte</p>

        <div class="requirements">
            <strong>🛡️ Exigences du mot de passe:</strong>
            <ul>Min. 8 caractères, 1 majuscule, 1 chiffre, 1 caractère spécial</ul>
        </div>

        <form method="POST" action="{{ route('password.setup.submit') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" value="{{ $email }}" disabled>
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
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
                       required>
            </div>

            <button type="submit" class="btn">
                ✓ Configurer mon compte
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