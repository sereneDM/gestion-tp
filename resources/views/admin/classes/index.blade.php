@extends('layouts.admin')

@section('title', 'Supervision des Classes')

@section('breadcrumb')
    <span class="tb-bc-page">Gestion</span>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">Classes</span>
@endsection

@section('topbar-actions')
    <a href="{{ route('admin.classes.create') }}" class="tb-btn tb-btn-primary">
        <i class="ti ti-plus"></i> Nouvelle classe
    </a>
@endsection

@section('extra-styles')
<style>
    .join-code {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        color: var(--accent);
        background: var(--accent-bg);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        letter-spacing: 0.05em;
        font-size: 11px;
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Supervision des Classes</h1>
<p class="page-subtitle">Consultez et gérez l'activité globale des classes.</p>

<div class="card" style="display:flex; align-items:flex-start; gap:10px; background:var(--accent-bg); border:1px solid rgba(61,90,254,.15); border-radius:var(--radius-md); padding:12px 14px; margin-bottom:24px; font-size:12.5px; color:var(--accent); line-height:1.5;">
    <i class="ti ti-info-circle" style="font-size:16px; flex-shrink:0; margin-top:1px;"></i>
    Les classes sont gérées par les enseignants. En tant qu'administrateur, vous pouvez superviser l'activité globale et intervenir si nécessaire.
</div>

<div class="card" style="overflow: hidden;">
    <div class="card-header">
        <div class="card-header-title"><i class="ti ti-books"></i> Toutes les classes</div>
        <span style="font-size: 11px; color: var(--ink-4);">{{ $classes->count() }} résultat(s)</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="admin-table">
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
                        <td><span style="font-weight:700; color:var(--ink);">{{ $class->name }}</span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <i class="ti ti-user-circle" style="color:var(--ink-4); font-size:16px;"></i>
                                <span style="font-weight:500;">{{ $class->teacher ? $class->teacher->name : 'Non assigné' }}</span>
                            </div>
                        </td>
                        <td><span class="join-code">{{ $class->join_code }}</span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:4px; color:var(--ink-3);">
                                <i class="ti ti-users" style="font-size:14px;"></i>
                                {{ $class->students_count }}
                            </div>
                        </td>
                        <td>
                            @if($class->status === 'active')
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge badge-archived">Archivée</span>
                            @endif
                        </td>
                        <td style="color:var(--ink-4); font-size:12px;">{{ $class->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.classes.show', $class->id) }}" class="btn-icon" title="Voir les détails">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn-icon" title="Modifier">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}" onsubmit="return confirm('Supprimer cette classe définitivement ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon danger" title="Supprimer">
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
@endsection