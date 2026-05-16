@extends('layouts.app')

@section('title', 'Détails de la Classe')

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
    :root {
        --ink:        #0d1117;
        --ink-2:      #3d4550;
        --ink-3:      #6b7585;
        --ink-4:      #9aa3af;
        --line:       #e8ebef;
        --line-2:     #d1d6dd;
        --surface:    #ffffff;
        --surface-2:  #f5f6f8;
        --surface-3:  #eef0f3;
        --accent:     #3d5afe;
        --accent-2:   #5271ff;
        --accent-bg:  #eef1ff;
        --danger:     #e53935;
        --warning:    #f59e0b;
        --success:    #10b981;
        --radius-sm:  6px;
        --radius-md:  10px;
        --radius-lg:  16px;
        --radius-xl:  22px;
        --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --font-body:  'DM Sans', sans-serif;
        --font-serif: 'DM Serif Display', serif;
    }

    .class-wrapper {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0.5rem 0 3rem;
    }

    .page-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .page-title {
        font-family: var(--font-serif);
        font-size: 2.25rem;
        color: var(--ink);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        font-size: 0.875rem;
        color: var(--ink-3);
        text-decoration: none;
        margin-bottom: 0.5rem;
        transition: color 0.2s;
    }

    .btn-back:hover {
        color: var(--accent);
    }

    .grid-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 2rem;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-xl);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
    }

    .card-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--ink-4);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--ink-4);
        text-transform: uppercase;
    }

    .info-val {
        font-size: 1rem;
        font-weight: 600;
        color: var(--ink);
    }

    .join-code-large {
        font-family: 'JetBrains Mono', monospace;
        font-size: 2rem;
        font-weight: 800;
        color: var(--accent);
        background: var(--accent-bg);
        padding: 1rem;
        border-radius: var(--radius-lg);
        text-align: center;
        margin: 1rem 0;
        letter-spacing: 0.1em;
        border: 2px dashed var(--accent);
    }

    .badge {
        display: inline-flex;
        padding: 0.25rem 0.75rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-active { background: #ecfdf5; color: #10b981; }
    .badge-archived { background: #fef2f2; color: #ef4444; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        padding: 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink-4);
        border-bottom: 1px solid var(--line);
    }

    td {
        padding: 1rem;
        font-size: 0.875rem;
        color: var(--ink-2);
        border-bottom: 1px solid var(--line);
    }

    tr:last-child td {
        border-bottom: none;
    }

    @media (max-width: 900px) {
        .grid-layout { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="class-wrapper">
    <a href="{{ route('admin.classes.index') }}" class="btn-back">
        <i class="ti ti-arrow-left"></i> Retour aux classes
    </a>
    
    <div class="page-header">
        <h1 class="page-title">{{ $class->name }}</h1>
        @if($class->status === 'active')
            <span class="badge badge-active">Active</span>
        @else
            <span class="badge badge-archived">Archivée</span>
        @endif
    </div>

    <div class="grid-layout">
        <div class="main-col">
            <div class="card">
                <div class="card-title"><i class="ti ti-info-circle"></i> Détails généraux</div>
                <div class="info-list">
                    @if($class->description)
                        <div class="info-item">
                            <div class="info-label">Description</div>
                            <div class="info-val" style="font-weight:400; line-height:1.6;">{{ $class->description }}</div>
                        </div>
                    @endif
                    
                    <div class="info-item">
                        <div class="info-label">Enseignant responsable</div>
                        <div class="info-val">
                            <i class="ti ti-user-circle"></i> 
                            {{ $class->teacher ? $class->teacher->name : 'Non assigné' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Date de création</div>
                        <div class="info-val">{{ $class->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="card" style="padding: 1rem 0;">
                <div class="card-title" style="padding: 0 2rem;"><i class="ti ti-users"></i> Étudiants inscrits ({{ $class->students->count() }})</div>
                <table>
                    <thead>
                        <tr>
                            <th style="padding-left: 2rem;">Nom</th>
                            <th>Email</th>
                            <th style="padding-right: 2rem;">Date d'inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($class->students as $student)
                            <tr>
                                <td style="padding-left: 2rem; font-weight:700;">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td style="padding-right: 2rem; color:var(--ink-4);">{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 4rem; color: var(--ink-4);">
                                    Aucun étudiant inscrit dans cette classe.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="side-col">
            <div class="card">
                <div class="card-title"><i class="ti ti-key"></i> Code d'accès</div>
                <div class="join-code-large">{{ $class->join_code }}</div>
                <p style="font-size: 0.75rem; color: var(--ink-4); text-align: center;">
                    Ce code permet aux étudiants de rejoindre la classe.
                </p>
            </div>

            <div class="card" style="background: var(--surface-2); border-color: transparent;">
                <div class="card-title"><i class="ti ti-activity"></i> Statistiques rapides</div>
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-label">Total Étudiants</div>
                        <div class="info-val" style="font-size:1.5rem;">{{ $class->students->count() }}</div>
                    </div>
                    {{-- You can add more stats here if available, e.g. active TPs --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection