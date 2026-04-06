@extends('layouts.teacher')

@section('title', 'Tableau de bord - Enseignant')
@section('page-title', 'Tableau de bord')

@section('content')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .welcome-card h2 {
        margin-bottom: 0.5rem;
    }
</style>

<div class="welcome-card">
    <h2>Bienvenue, {{ Auth::user()->name }}</h2>
    <p>Utilisez le menu de gauche pour naviguer dans vos fonctionnalités enseignant</p>
</div>

@endsection