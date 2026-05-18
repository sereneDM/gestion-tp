@extends('layouts.admin')

@section('title', 'Détails de la Classe')

@section('breadcrumb')
    <a href="{{ route('admin.classes.index') }}" class="tb-bc-page" style="text-decoration:none;">Cours</a>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">{{ $class->name }}</span>
@endsection

@section('extra-styles')
<style>
    .grid-layout {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 20px;
    }

    .join-code-large {
        font-family: 'JetBrains Mono', monospace;
        font-size: 2rem;
        font-weight: 800;
        color: var(--accent);
        background: var(--accent-bg);
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        text-align: center;
        margin: 1rem 0;
        letter-spacing: 0.1em;
        border: 2px dashed var(--accent);
    }

    @media (max-width: 900px) {
        .grid-layout { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<h1 class="page-title">{{ $class->name }}</h1>
<div style="display:flex; align-items:center; gap:10px; margin-bottom:1.75rem;">
    @if($class->status === 'active')
        <span class="badge badge-active">Active</span>
    @else
        <span class="badge badge-archived">Archivée</span>
    @endif
    <span style="color:var(--ink-4); font-size:13px;">Créée le {{ $class->created_at->format('d/m/Y à H:i') }}</span>
</div>

<div class="grid-layout">
    <div class="main-col">
        <div class="card" style="padding: 28px; margin-bottom:20px;">
            <div class="card-header-title" style="margin-bottom:1.5rem;"><i class="ti ti-info-circle"></i> Informations générales</div>
            
            <div class="form-group">
                <label class="label">Description</label>
                <p style="font-size: 14px; color: var(--ink-2); line-height: 1.6;">
                    {{ $class->description ?: 'Aucune description fournie.' }}
                </p>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="label">Enseignant responsable</label>
                <div style="display:flex; align-items:center; gap:8px; font-weight:600; color:var(--ink);">
                    <i class="ti ti-user-circle" style="font-size:20px; color:var(--ink-4);"></i>
                    {{ $class->teacher ? $class->teacher->name : 'Non assigné' }}
                </div>
            </div>
        </div>

        <div class="card" style="overflow: hidden;">
            <div class="card-header">
                <div class="card-header-title"><i class="ti ti-users"></i> Étudiants inscrits</div>
                <span style="font-size: 11px; color: var(--ink-4);">{{ $class->students->count() }} inscrit(s)</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Email</th>
                            <th>Date d'inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($class->students as $student)
                            <tr>
                                <td style="font-weight:700; color:var(--ink);">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td style="color:var(--ink-4); font-size:12px;">{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 4rem; color: var(--ink-4);">
                                    <i class="ti ti-users" style="font-size:2rem; display:block; margin-bottom:1rem; opacity:0.4;"></i>
                                    Aucun étudiant inscrit dans ce cours.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="side-col">
        <div class="card" style="padding: 24px; margin-bottom:20px;">
            <div class="card-header-title" style="margin-bottom:1rem;"><i class="ti ti-key"></i> Code d'accès</div>
            <div class="join-code-large">{{ $class->join_code }}</div>
            <p style="font-size: 11px; color: var(--ink-4); text-align: center; line-height: 1.4;">
                Ce code unique permet aux étudiants de rejoindre ce cours via leur portail.
            </p>
        </div>

        <div class="card" style="padding: 24px; background: var(--surface-2); border-color: transparent;">
            <div class="card-header-title" style="margin-bottom:1rem;"><i class="ti ti-activity"></i> Statistiques</div>
            <div class="stat-tile" style="box-shadow:none; padding:0; background:transparent; border:none;">
                <div class="stat-tile-label">Effectif</div>
                <div class="stat-tile-value">{{ $class->students->count() }}</div>
                <div class="stat-tile-sub">étudiants actifs</div>
            </div>
        </div>
        
        <div style="margin-top:20px; display:flex; flex-direction:column; gap:10px;">
            <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-secondary" style="flex:none; width:100%;">
                <i class="ti ti-edit"></i> Modifier le cours
            </a>
            <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}" onsubmit="return confirm('Supprimer ce cours définitivement ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-secondary" style="flex:none; width:100%; color:var(--danger); border-color:rgba(229, 57, 53, 0.2);">
                    <i class="ti ti-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection