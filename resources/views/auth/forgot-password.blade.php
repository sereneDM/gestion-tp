<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
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
        <h1 class="text-2xl font-bold text-center mb-2 text-slate-900 dark:text-white">🔐 Mot de passe oublié</h1>
        <p class="text-center text-slate-600 dark:text-slate-400 text-sm mb-6">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-4 text-sm">✓ {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block mb-2 text-slate-700 dark:text-slate-200 font-medium">Adresse email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-400">
                @error('email')
                    <div class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-violet-600 dark:bg-violet-600 hover:bg-violet-700 dark:hover:bg-violet-700 text-white font-semibold rounded-lg transition-colors duration-200">📧 Envoyer le lien</button>
        </form>

        <a href="{{ route('login') }}" class="block text-center mt-4 text-violet-600 dark:text-violet-400 text-sm hover:underline">← Retour à la connexion</a>
    </div>
</body>
</html>