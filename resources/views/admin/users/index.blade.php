@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs & Droits d\'Accès')

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
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-warning {
        background-color: #ffc107;
        color: #333;
    }
    .btn-warning:hover {
        background-color: #e0a800;
    }
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background-color: #c82333;
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
    .role-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
    }
    .role-student {
        background-color: #e3f2fd;
        color: #1976d2;
    }
    .role-teacher {
        background-color: #fff3e0;
        color: #f57c00;
    }
    .role-admin {
        background-color: #ffebee;
        color: #c62828;
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
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9rem;
        background: white;
    }
    select:focus {
        outline: none;
        border-color: #007bff;
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
                            <select name="role" onchange="if(confirm('Changer le rôle de cet utilisateur?')) this.form.submit();">
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