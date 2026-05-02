<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme TP</title>
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
            height: 100vh;
            margin: 0;
        }
        .login-container {
            @apply bg-white dark:bg-[#1e293b] shadow-lg rounded-lg p-8 w-full max-w-md;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            @apply text-2xl font-bold text-center mb-6 text-slate-900 dark:text-white;
        }
        .form-group {
            @apply mb-4;
        }
        label {
            @apply block mb-2 text-slate-700 dark:text-slate-200 font-medium;
        }
        input {
            @apply w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-400;
        }
        button {
            @apply w-full py-2 px-4 bg-violet-600 dark:bg-violet-600 hover:bg-violet-700 dark:hover:bg-violet-700 text-white font-semibold rounded-lg transition-colors duration-200;
        }
        .error {
            @apply text-red-600 dark:text-red-400 text-sm mt-1;
        }
        .forgot-password {
            @apply text-right mt-2;
        }
        .forgot-password a {
            @apply text-violet-600 dark:text-violet-400 text-sm hover:underline;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0f172a]">
    <div class="login-container bg-white dark:bg-[#1e293b] shadow-lg rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6 text-slate-900 dark:text-white">Plateforme de Gestion des TP</h1>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group mb-4">
                <label for="email" class="block mb-2 text-slate-700 dark:text-slate-200 font-medium">Email</label>
                <input type="email" id="email" name="email" required autofocus value="{{ old('email') }}" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-400">
                @error('email')
                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

           <div class="form-group mb-4">
                <label for="password" class="block mb-2 text-slate-700 dark:text-slate-200 font-medium">Mot de passe</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-400">
                @error('password')
                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                @enderror
                <div class="text-right mt-2">
                    <a href="{{ route('password.forgot') }}" class="text-violet-600 dark:text-violet-400 text-sm hover:underline">Mot de passe oublié ?</a>
                </div>
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-violet-600 dark:bg-violet-600 hover:bg-violet-700 dark:hover:bg-violet-700 text-white font-semibold rounded-lg transition-colors duration-200">Se connecter</button>
        </form>
    </div>
</body>
</html>