@extends('layouts.app')

@section('title', 'Supervision des Classes')
@section('page-title', 'Supervision des Classes')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-info {
        background-color: #17a2b8;
        color: white;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .info-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 4px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }
    thead {
        background-color: #007bff;
        color: white;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    tbody tr:hover {
        background-color: #f8f9fa;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    .status-archived {
        background-color: #f8d7da;
        color: #721c24;
    }
    .join-code {
        font-family: monospace;
        background: #e7f3ff;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        font-weight: bold;
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