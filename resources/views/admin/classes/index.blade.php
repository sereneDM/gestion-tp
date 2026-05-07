@extends('layouts.app')

@section('title', 'Supervision des Classes')
@section('page-title', 'Supervision des Classes')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        background: #1e293b;
        color: #e2e8f0;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn:hover {
        background: #334155;
        border-color: #475569;
    }
    .btn-secondary {
        background-color: #1e293b;
        color: #e2e8f0;
    }
    .btn-secondary:hover {
        background: #334155;
    }
    .btn-info {
        background-color: #1e293b;
        color: #e2e8f0;
    }
    .btn-info:hover {
        background: #334155;
    }
    .btn-danger {
        background-color: #1e293b;
        color: #fca5a5;
        border-color: #7f1d1d;
    }
    .btn-danger:hover {
        background: #7f1d1d;
        border-color: #991b1b;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .alert {
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
    }
    .alert-success {
        background-color: rgba(34, 197, 94, 0.1);
        color: #86efac;
        border: 1px solid #16a34a;
    }
    .info-box {
        background: rgba(99, 102, 241, 0.1);
        border-left: 4px solid #6366f1;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 0.75rem;
        color: #c7d2fe;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #0f172a;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        overflow: hidden;
    }
    thead {
        background-color: #1e293b;
        border-bottom: 2px solid #334155;
    }
    th {
        padding: 1rem;
        text-align: left;
        color: #cbd5e1;
        font-weight: bold;
    }
    td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #334155;
        color: #e2e8f0;
    }
    tbody tr:hover {
        background-color: #1e293b;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .status-active {
        background-color: rgba(34, 197, 94, 0.15);
        color: #86efac;
    }
    .status-archived {
        background-color: rgba(244, 63, 94, 0.15);
        color: #ff6b9d;
    }
    .join-code {
        font-family: monospace;
        background: #1e293b;
        padding: 0.3rem 0.6rem;
        border-radius: 0.75rem;
        font-weight: bold;
        color: #818cf8;
        border: 1px solid #334155;
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
</style>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="info-box">
        ℹ️ <strong>Note:</strong> Les classes sont maintenant créées par les enseignants eux-mêmes. Vous pouvez les superviser et les supprimer si nécessaire.
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom de la Classe</th>
                <th>Enseignant</th>
                <th>Code d'accès</th>
                <th>Étudiants</th>
                <th>Statut</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classes as $class)
                <tr>
                    <td><strong>{{ $class->name }}</strong></td>
                    <td>{{ $class->teacher ? $class->teacher->name : 'Non assigné' }}</td>
                    <td><span class="join-code">{{ $class->join_code }}</span></td>
                    <td>{{ $class->students_count }}</td>
                    <td>
                        <span class="status-badge status-{{ $class->status }}">
                            {{ $class->status === 'active' ? 'Actif' : 'Archivé' }}
                        </span>
                    </td>
                    <td>{{ $class->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.classes.show', $class->id) }}" 
                               class="btn btn-info btn-small">
                                👁️ Voir
                            </a>
                            <form method="POST" 
                                  action="{{ route('admin.classes.destroy', $class->id) }}"
                                  onsubmit="return confirm('Supprimer cette classe? Cette action est irréversible!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-small">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: #999;">
                        Aucune classe créée
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection