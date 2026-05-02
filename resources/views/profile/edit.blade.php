@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page-title', 'Paramètres de Mon Profil')

@section('content')

@if(session('info'))
    <div class="mb-6 p-4 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg">
        📧 {{ session('info') }}
    </div>
@endif

<div class="grid md:grid-cols-2 gap-6">

    <!-- PROFILE -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-6">

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-700 pb-2">
            📧 Informations Personnelles
        </h2>

        @if($user->profile_picture)
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ $user->profile_picture_url }}"
                     class="w-20 h-20 rounded-full object-cover border-4 border-violet-500">

                <form method="POST" action="{{ route('profile.delete-picture') }}">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>
        @endif

        <form id="profile-form" method="POST" action="{{ route('profile.update-info') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="text-sm text-slate-700 dark:text-slate-300">Photo de profil</label>
                <input type="file" name="profile_picture"
                       class="w-full mt-1 bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-300 dark:border-slate-600 rounded p-2">
            </div>

            <div class="mb-4">
                <label class="text-sm text-slate-700 dark:text-slate-300">
                    Nom complet (2–20 caractères)
                </label>

                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full mt-1 bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-300 dark:border-slate-600 rounded p-2">

                @error('name') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            <button class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded">
                ✓ Enregistrer
            </button>

        </form>
    </div>

    <!-- PASSWORD -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-6">

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-700 pb-2">
            🔒 Changer le mot de passe
        </h2>

        <form method="POST" action="{{ route('profile.update-password') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="text-sm text-slate-700 dark:text-slate-300">Mot de passe actuel</label>
                <input type="password" name="current_password"
                       class="w-full mt-1 bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-300 dark:border-slate-600 rounded p-2">
            </div>

            <div class="mb-4">
                <label class="text-sm text-slate-700 dark:text-slate-300">Nouveau mot de passe</label>
                <input type="password" name="new_password"
                       class="w-full mt-1 bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-300 dark:border-slate-600 rounded p-2">
            </div>

            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🔑 Modifier
            </button>

        </form>
    </div>

</div>

@endsection