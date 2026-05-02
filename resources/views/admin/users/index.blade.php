@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs & Droits d\'Accès')

@section('extra-styles')
<style>
    .btn {
        @apply px-5 py-3 border-none rounded-2xl cursor-pointer no-underline text-sm inline-flex items-center justify-center font-medium transition-colors duration-200;
    }
    .btn-primary {
        @apply bg-violet-600 dark:bg-violet-600 text-white hover:bg-violet-700 dark:hover:bg-violet-700;
    }
    .btn-warning {
        @apply bg-amber-500 dark:bg-amber-600 text-slate-900 dark:text-white hover:bg-amber-600 dark:hover:bg-amber-700;
    }
    .btn-danger {
        @apply bg-red-600 dark:bg-red-600 text-white hover:bg-red-700 dark:hover:bg-red-700;
    }
    .btn-small {
        @apply px-3 py-2 text-xs;
    }
    .header-actions {
        @apply mb-6 text-right;
    }
    table {
        @apply w-full border-collapse bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-200;
    }
    thead {
        @apply bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-slate-50 font-semibold;
    }
    th, td {
        @apply px-4 py-3 text-left border-b border-slate-200 dark:border-slate-700;
    }
    tbody tr:hover {
        @apply bg-slate-50 dark:bg-slate-700/50 transition-colors;
    }
    .role-badge {
        @apply px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center;
    }
    .role-student {
        @apply bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300;
    }
    .role-teacher {
        @apply bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300;
    }
    .role-admin {
        @apply bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300;
    }
    .action-buttons {
        @apply flex gap-2;
    }
    .delete-form {
        @apply inline;
    }
    select {
        @apply px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500 dark:focus:ring-violet-400;
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