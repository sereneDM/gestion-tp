@extends('layouts.app')

@section('title', 'Tableau de bord - Admin')
@section('page-title', 'Panneau d\'Administration')

@section('extra-styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #4f46e5 0%, #8b5cf6 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        border: 1px solid #334155;
    }
    .welcome-card h2 {
        margin-bottom: 0.5rem;
    }
    .welcome-card p {
        margin: 0.5rem 0 0 0;
        color: #e0e7ff;
    }
</style>
@endsection

@section('content')
<div class="welcome-card">
    <h2>Bienvenue, {{ Auth::user()->name }}</h2>
    <p>Vous êtes connecté en tant qu'administrateur</p>
    <p>Utilisez le menu de gauche pour gérer la plateforme</p>
</div>
@endsection