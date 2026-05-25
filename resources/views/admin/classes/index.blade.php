@extends('layouts.admin')

@section('title', 'Supervision des Classes')

@section('breadcrumb')
    <span class="tb-bc-current">Cours</span>
@endsection

@section('topbar-actions')
    <a href="{{ route('admin.classes.create') }}" class="tb-btn tb-btn-primary">
        <i class="ti ti-plus"></i> Nouveau cours
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

    /* ── Checkbox column ── */
    .cb-cell {
        width: 36px;
        padding-left: 16px !important;
        padding-right: 4px !important;
    }

    .row-checkbox,
    .select-all-cb {
        width: 15px;
        height: 15px;
        accent-color: var(--accent);
        cursor: pointer;
    }

    /* ── Bulk action bar ── */
    .bulk-bar {
        display: none;
        align-items: center;
        gap: 10px;
        background: var(--accent-bg);
        border: 1px solid rgba(61,90,254,.2);
        border-radius: var(--radius-md);
        padding: 9px 14px;
        margin-bottom: 14px;
        font-size: 12.5px;
        color: var(--accent);
        font-weight: 600;
        animation: slideDown .15s ease;
    }

    .bulk-bar.visible { display: flex; }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .bulk-bar-count { flex: 1; }

    .btn-bulk-delete {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px;
        border-radius: var(--radius-sm);
        border: none;
        background: var(--danger);
        color: white;
        font-size: 12px; font-weight: 700;
        font-family: inherit; cursor: pointer;
        transition: background .15s;
    }
    .btn-bulk-delete:hover { background: #c62828; }
    .btn-bulk-delete i { font-size: 14px; }

    .btn-bulk-cancel {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 6px 10px;
        border-radius: var(--radius-sm);
        border: 1px solid rgba(61,90,254,.2);
        background: transparent;
        color: var(--accent);
        font-size: 12px; font-weight: 600;
        font-family: inherit; cursor: pointer;
        transition: background .15s;
    }
    .btn-bulk-cancel:hover { background: rgba(61,90,254,.08); }
</style>
@endsection

@section('content')
<h1 class="page-title">Supervision des Cours</h1>
<p class="page-subtitle">Consultez et gérez l'activité globale des cours.</p>

<div class="card" style="display:flex; align-items:flex-start; gap:10px; background:var(--accent-bg); border:1px solid rgba(61,90,254,.15); border-radius:var(--radius-md); padding:12px 14px; margin-bottom:24px; font-size:12.5px; color:var(--accent); line-height:1.5;">
    <i class="ti ti-info-circle" style="font-size:16px; flex-shrink:0; margin-top:1px;"></i>
    Les cours sont gérés par les enseignants. En tant qu'administrateur, vous pouvez superviser l'activité globale et intervenir si nécessaire.
</div>

{{-- Bulk action bar --}}
<div class="bulk-bar" id="bulk-bar">
    <span class="bulk-bar-count" id="bulk-count-label">0 sélectionné(s)</span>
    <form method="POST" action="{{ route('admin.classes.bulk-destroy') }}" id="bulk-delete-form" style="display:contents;">
        @csrf
        @method('DELETE')
        <div id="bulk-ids-container"></div>
        <button type="submit" class="btn-bulk-delete">
            <i class="ti ti-trash"></i> Supprimer la sélection
        </button>
    </form>
    <button type="button" class="btn-bulk-cancel" onclick="clearSelection()">
        <i class="ti ti-x" style="font-size:12px;"></i> Annuler
    </button>
</div>

<div class="card" style="overflow: hidden;">
    <div class="card-header">
        <div class="card-header-title"><i class="ti ti-books"></i> Tous les cours</div>
        <span style="font-size: 11px; color: var(--ink-4);">{{ $classes->count() }} résultat(s)</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="cb-cell">
                        <input type="checkbox" class="select-all-cb" id="select-all" title="Tout sélectionner">
                    </th>
                    <th>Cours</th>
                    <th>Enseignant</th>
                    <th>Code d'accès</th>
                    <th>Étudiants</th>
                    <th>Statut</th>
                    <th>Créée le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="classes-tbody">
                @forelse($classes as $class)
                    <tr data-class-id="{{ $class->id }}">
                        <td class="cb-cell">
                            <input type="checkbox"
                                   class="row-checkbox"
                                   value="{{ $class->id }}"
                                   title="Sélectionner {{ $class->name }}">
                        </td>
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
                                <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}" onsubmit="return confirm('Supprimer ce cours définitivement ?')">
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
                        <td colspan="8" style="text-align:center; padding:4rem; color:var(--ink-4);">
                            <i class="ti ti-book" style="font-size:2rem; display:block; margin-bottom:1rem; opacity:0.5;"></i>
                            Aucun cours enregistré
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // ── Multi-select logic ──
    const selectAll    = document.getElementById('select-all');
    const bulkBar      = document.getElementById('bulk-bar');
    const countLabel   = document.getElementById('bulk-count-label');
    const idsContainer = document.getElementById('bulk-ids-container');
    const bulkDeleteForm = document.getElementById('bulk-delete-form');

    function getChecked() {
        return [...document.querySelectorAll('.row-checkbox:checked')];
    }

    function updateBulkBar() {
        const checked = getChecked();
        if (checked.length > 0) {
            bulkBar.classList.add('visible');
            countLabel.textContent = checked.length + ' cours sélectionné(s)';

            idsContainer.innerHTML = '';
            checked.forEach(cb => {
                const inp = document.createElement('input');
                inp.type  = 'hidden';
                inp.name  = 'ids[]';
                inp.value = cb.value;
                idsContainer.appendChild(inp);
            });
        } else {
            bulkBar.classList.remove('visible');
            idsContainer.innerHTML = '';
        }

        const allCbs = [...document.querySelectorAll('.row-checkbox')];
        selectAll.indeterminate = checked.length > 0 && checked.length < allCbs.length;
        selectAll.checked       = allCbs.length > 0 && checked.length === allCbs.length;
    }

    selectAll.addEventListener('change', () => {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = selectAll.checked;
        });
        updateBulkBar();
    });

    document.getElementById('classes-tbody').addEventListener('change', e => {
        if (e.target.classList.contains('row-checkbox')) updateBulkBar();
    });

    function clearSelection() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        selectAll.checked = false;
        selectAll.indeterminate = false;
        updateBulkBar();
    }

    // Confirm before bulk delete
    bulkDeleteForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const count = getChecked().length;
        customConfirm(
            `Supprimer ${count} cours ? Cette action est irréversible et supprimera toutes les données associées.`,
            '<i class="ti ti-trash" style="color:#e53935"></i>'
        ).then(ok => { if (ok) this.submit(); });
    });
</script>
@endsection