@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')

<div class="bg-gradient-to-r from-violet-600 to-indigo-600 text-white px-8 py-8 rounded-2xl mb-6 shadow-lg">
    <h2 class="text-2xl font-bold mb-1">👋 Bienvenue, {{ Auth::user()->name }}</h2>
    <p class="text-violet-100 my-1">Vous êtes connecté en tant qu'administrateur</p>
    <p class="text-violet-200 text-sm">Utilisez le menu de navigation pour gérer la plateforme</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 border border-slate-200 dark:border-slate-600 text-center">
        <div class="text-3xl mb-2">👥</div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">—</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Utilisateurs</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 border border-slate-200 dark:border-slate-600 text-center">
        <div class="text-3xl mb-2">🏫</div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">—</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Classes</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 border border-slate-200 dark:border-slate-600 text-center">
        <div class="text-3xl mb-2">📚</div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">—</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">Cours</div>
    </div>
    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5 border border-slate-200 dark:border-slate-600 text-center">
        <div class="text-3xl mb-2">📝</div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">—</div>
        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">TPs</div>
    </div>
</div>

@endsection