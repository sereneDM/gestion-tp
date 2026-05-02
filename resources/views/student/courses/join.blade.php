@extends('layouts.app')
@section('title', 'Rejoindre un cours')

@section('content')

<div class="max-w-md mx-auto">
    <div class="text-center text-7xl mb-4">🎓</div>
    <h2 class="text-xl font-bold text-slate-900 dark:text-white text-center mb-6">Rejoindre un Cours</h2>

    <div class="flex items-start gap-2 bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-indigo-500
                px-4 py-3 rounded-lg text-sm text-indigo-700 dark:text-indigo-300 mb-6">
        ℹ️ Entrez le code d'accès fourni par votre enseignant (format: XXX-XXX-123)
    </div>

    <form method="POST" action="{{ route('student.join-course') }}">
        @csrf
        <div class="mb-5">
            <label for="join_code"
                   class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                Code d'accès
            </label>
            <input type="text"
                   id="join_code"
                   name="join_code"
                   value="{{ old('join_code') }}"
                   placeholder="EQY-ZIH-439"
                   maxlength="11"
                   required
                   autofocus
                   class="w-full px-4 py-3 rounded-lg border-2 border-slate-300 dark:border-slate-600
                          bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100
                          text-center text-lg font-mono tracking-widest uppercase
                          focus:outline-none focus:border-violet-500 transition-colors
                          placeholder-slate-400 dark:placeholder-slate-500">
            @error('join_code')
                <div class="text-red-500 dark:text-red-400 text-sm mt-1.5 text-center">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-3 bg-violet-600 hover:bg-violet-700 text-white rounded-lg
                       font-bold text-base transition-colors">
            ✓ Rejoindre le cours
        </button>
    </form>
</div>

@endsection