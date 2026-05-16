@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

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

    .users-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0.5rem 0 3rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: var(--font-serif);
        font-size: 2rem;
        color: var(--ink);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: 1px solid transparent;
    }

    .btn-primary {
        background: var(--accent);
        color: white;
    }

    .btn-primary:hover {
        background: var(--accent-2);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(61, 90, 254, 0.2);
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
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: var(--ink-2);
        border-bottom: 1px solid var(--line);
        vertical-align: middle;
    }

    tr:hover td {
        background: var(--surface-2);
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 700;
        color: var(--ink);
    }

    .user-email {
        font-size: 0.75rem;
        color: var(--ink-4);
    }

    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-student { background: #eef1ff; color: var(--accent); }
    .badge-teacher { background: #fffbeb; color: var(--warning); }
    .badge-admin { background: #fef2f2; color: var(--danger); }

    .role-select {
        padding: 0.4rem 2rem 0.4rem 0.75rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        font-size: 0.8rem;
        background: var(--surface);
        color: var(--ink-2);
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239aa3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 1rem;
    }

    .role-select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-bg);
    }

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

    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    }
</style>
@endsection

@section('content')
<div class="users-wrapper">
    <div class="page-header">
        <h1 class="page-title">Gestion des Utilisateurs</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Nouvel Utilisateur
        </a>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rôle Actuel</th>
                        <th>Changer le rôle</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <span class="user-name">{{ $user->name }}</span>
                                    <span class="user-email">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'student')
                                    <span class="badge badge-student">Étudiant</span>
                                @elseif($user->role === 'teacher')
                                    <span class="badge badge-teacher">Enseignant</span>
                                @else
                                    <span class="badge badge-admin">Admin</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.update-role', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="role-select" onchange="this.form.submit()">
                                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Étudiant</option>
                                        <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Enseignant</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td style="color:var(--ink-4); font-size:0.8rem;">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon" title="Modifier">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    @if(Auth::id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon delete" title="Supprimer">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:4rem; color:var(--ink-4);">
                                <i class="ti ti-users" style="font-size:2rem; display:block; margin-bottom:1rem; opacity:0.5;"></i>
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection