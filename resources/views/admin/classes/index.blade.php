@extends('layouts.app')

@section('title', 'Supervision des Classes')

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
        --shadow-md:  0 4px 16px rgba(0,0,0,0.07);
        --font-body:  'DM Sans', sans-serif;
        --font-serif: 'DM Serif Display', serif;
    }

    .classes-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0.5rem 0 3rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: var(--font-serif);
        font-size: 2rem;
        color: var(--ink);
    }

    .info-box {
        background: var(--surface-3);
        border-radius: var(--radius-lg);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: var(--ink-3);
        margin-bottom: 2rem;
        border: 1px solid var(--line);
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-4);
        border-bottom: 1px solid var(--line);
        background: var(--surface-2);
    }

    td {
        padding: 1.25rem 1.5rem;
        font-size: 0.875rem;
        color: var(--ink-2);
        border-bottom: 1px solid var(--line);
        vertical-align: middle;
    }

    tr:hover td {
        background: var(--surface-2);
    }

    .class-name {
        font-weight: 700;
        color: var(--ink);
    }

    .teacher-name {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .join-code {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        color: var(--accent);
        background: var(--accent-bg);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        letter-spacing: 0.05em;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active { background: #ecfdf5; color: #10b981; }
    .badge-archived { background: #fef2f2; color: #ef4444; }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ink-3);
        border: 1px solid var(--line);
        background: var(--surface);
        transition: all 0.2s;
    }

    .btn-icon:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: var(--accent-bg);
    }

    .btn-icon.delete:hover {
        border-color: var(--danger);
        color: var(--danger);
        background: #fef2f2;
    }
</style>
@endsection

@section('content')
<div class="classes-wrapper">
    <div class="page-header">
        <h1 class="page-title">Supervision des Classes</h1>
    </div>

    <div class="info-box">
        <i class="ti ti-info-circle"></i>
        <span>Les classes sont gérées par les enseignants. En tant qu'administrateur, vous pouvez superviser l'activité globale et intervenir si nécessaire.</span>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Classe</th>
                        <th>Enseignant</th>
                        <th>Code d'accès</th>
                        <th>Étudiants</th>
                        <th>Statut</th>
                        <th>Créée le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td><span class="class-name">{{ $class->name }}</span></td>
                            <td>
                                <div class="teacher-name">
                                    <i class="ti ti-user-circle" style="color:var(--ink-4);"></i>
                                    {{ $class->teacher ? $class->teacher->name : 'Non assigné' }}
                                </div>
                            </td>
                            <td><span class="join-code">{{ $class->join_code }}</span></td>
                            <td><i class="ti ti-users" style="margin-right:4px; color:var(--ink-4);"></i> {{ $class->students_count }}</td>
                            <td>
                                @if($class->status === 'active')
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-archived">Archivée</span>
                                @endif
                            </td>
                            <td style="color:var(--ink-4);">{{ $class->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.classes.show', $class->id) }}" class="btn-icon" title="Voir les détails">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}" onsubmit="return confirm('Supprimer cette classe définitivement ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete" title="Supprimer">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:4rem; color:var(--ink-4);">
                                <i class="ti ti-book" style="font-size:2rem; display:block; margin-bottom:1rem; opacity:0.5;"></i>
                                Aucune classe enregistrée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection