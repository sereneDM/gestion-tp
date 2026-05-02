<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
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
</head>
<body class="bg-slate-50 dark:bg-[#0f172a] min-h-screen flex items-center justify-center">
    <div class="bg-white dark:bg-[#1e293b] shadow-lg rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6 text-slate-900 dark:text-white">🔑 Nouveau mot de passe</h1>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-4">
                <label for="password" class="block mb-2 text-slate-700 dark:text-slate-200 font-medium">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-400">
                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Min. 8 caractères, 1 majuscule, 1 chiffre, 1 caractère spécial</div>
                @error('password')
                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block mb-2 text-slate-700 dark:text-slate-200 font-medium">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-400">
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-green-600 dark:bg-green-600 hover:bg-green-700 dark:hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200">✓ Réinitialiser mon mot de passe</button>
        </form>
    </div>
</body>
</html>