@extends('layouts.admin')

@section('title', 'Tableau de bord - Admin')
@section('page-title', 'Panneau d\'Administration')

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
    <p>Vous êtes connecté en tant qu'administrateur</p>
    <p>Utilisez le menu de gauche pour gérer la plateforme</p>
</div>
@endsection