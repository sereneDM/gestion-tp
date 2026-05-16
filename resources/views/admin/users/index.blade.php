@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('breadcrumb')
    <span class="tb-bc-page">Gestion</span>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">Utilisateurs</span>
@endsection

@section('topbar-actions')
    <a href="{{ route('admin.users.create') }}" class="tb-btn tb-btn-primary">
        <i class="ti ti-plus"></i> Nouvel utilisateur
    </a>
@endsection

@section('content')
<div class="card" style="overflow: hidden;">
    <div class="card-header">
        <div class="card-header-title"><i class="ti ti-users"></i> Tous les utilisateurs</div>
        <span style="font-size: 11px; color: var(--ink-4);">{{ $users->count() }} résultat(s)</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Rôle actuel</th>
                    <th>Changer le rôle</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:50%; background:var(--accent-bg); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:var(--accent); flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700; color:var(--ink); font-size:13px;">{{ $user->name }}</div>
                                    <div style="font-size:11px; color:var(--ink-4);">{{ $user->email }}</div>
                                </div>
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
                                @csrf @method('PUT')
                                <select name="role" onchange="this.form.submit()" style="padding: 5px 28px 5px 10px; border-radius: var(--radius-sm); border: 1px solid var(--line); font-size: 12px; background: var(--surface); color: var(--ink-2); cursor: pointer; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%239aa3af\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position: right 6px center; background-size: 14px;">
                                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Étudiant</option>
                                    <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Enseignant</option>
                                    <option value="admin"   {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td style="color:var(--ink-4); font-size:12px;">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon" title="Modifier">
                                    <i class="ti ti-edit"></i>
                                </a>
                                @if(Auth::id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon danger" title="Supprimer">
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
                            <i class="ti ti-users" style="font-size:2rem; display:block; margin-bottom:.75rem; opacity:.4;"></i>
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection