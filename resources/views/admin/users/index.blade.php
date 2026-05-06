@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs & Droits d\'Accès')

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
    .btn-primary {
        background-color: #1e293b;
        color: #e2e8f0;
    }
    .btn-primary:hover {
        background-color: #334155;
    }
    .btn-warning {
        background-color: #1e293b;
        color: #fbbf24;
        border-color: #92400e;
    }
    .btn-warning:hover {
        background-color: #92400e;
    }
    .btn-danger {
        background-color: #1e293b;
        color: #fca5a5;
        border-color: #7f1d1d;
    }
    .btn-danger:hover {
        background-color: #7f1d1d;
    }
    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
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
    .role-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .role-student {
        background-color: rgba(99, 102, 241, 0.15);
        color: #c7d2fe;
    }
    .role-teacher {
        background-color: rgba(251, 191, 36, 0.15);
        color: #fef08a;
    }
    .role-admin {
        background-color: rgba(244, 63, 94, 0.15);
        color: #ff6b9d;
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    .delete-form {
        display: inline;
    }
    select {
        padding: 0.5rem;
        border: 1px solid #475569;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        background: #1e293b;
        color: #e2e8f0;
        cursor: pointer;
        transition: all 0.2s;
    }
    select:hover {
        border-color: #6366f1;
        background: #334155;
    }
    select:focus {
        outline: none;
        border-color: #6366f1;
        background: #334155;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    /* Option styling for dropdown items */
    select option {
        background: #1e293b;
        color: #e2e8f0;
    }
</style>
@endsection

@section('content')
    <div class="header-actions">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            ➕ Ajouter un utilisateur
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Changer le rôle</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="role-badge role-{{ $user->role }}">
                            @if($user->role === 'student')
                                Étudiant
                            @elseif($user->role === 'teacher')
                                Enseignant
                            @else
                                Administrateur
                            @endif
                        </span>
                    </td>
                    <td>
                        <form method="POST" 
                              action="{{ route('admin.users.update-role', $user->id) }}"
                              style="display: inline;">
                            @csrf
                            @method('PUT')
                            <select name="role" onchange="handleRoleChange(this)">
                                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>
                                    Étudiant
                                </option>
                                <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>
                                    Enseignant
                                </option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                                    Administrateur
                                </option>
                            </select>
                        </form>
                    </td>
                    <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="btn btn-warning btn-small">
                                ✏️ Modifier
                            </a>
                            <form method="POST" 
                                  action="{{ route('admin.users.destroy', $user->id) }}"
                                  class="delete-form"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
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
                        Aucun utilisateur trouvé
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection