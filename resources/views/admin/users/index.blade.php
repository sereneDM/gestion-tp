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

@section('extra-styles')
<style>
    /* Filter strip */
    .filter-strip {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    /* Search input */
    .filter-search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 320px;
    }

    .filter-search-wrap i {
        position: absolute; left: 10px; top: 50%;
        transform: translateY(-50%);
        font-size: 14px; color: var(--ink-4); pointer-events: none;
    }

    .filter-search {
        width: 100%;
        padding: 7px 10px 7px 32px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 12.5px; font-family: inherit;
        background: var(--surface); color: var(--ink);
        transition: border-color .2s, box-shadow .2s;
    }

    .filter-search:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-bg); }
    .filter-search::placeholder { color: var(--ink-4); }

    /* Role tabs */
    .role-tabs {
        display: flex;
        gap: 4px;
        background: var(--surface-3);
        border-radius: var(--radius-sm);
        padding: 3px;
        border: 1px solid var(--line);
    }

    .role-tab {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 12px; font-weight: 600;
        text-decoration: none; color: var(--ink-3);
        transition: background .15s, color .15s;
        white-space: nowrap;
    }

    .role-tab:hover { background: var(--surface); color: var(--ink-2); }

    .role-tab.active-all      { background: var(--surface); color: var(--ink-2); box-shadow: 0 1px 3px rgba(0,0,0,.07); }
    .role-tab.active-student  { background: #eff6ff; color: #1d4ed8; }
    .role-tab.active-teacher  { background: #fff7ed; color: #c2410c; }
    .role-tab.active-admin    { background: var(--accent-bg); color: var(--accent); }

    .role-tab i { font-size: 13px; }

    /* Sort select */
    .filter-sort {
        padding: 7px 28px 7px 10px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 12.5px; font-family: inherit;
        background: var(--surface); color: var(--ink-2);
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239aa3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 14px;
        transition: border-color .2s;
    }

    .filter-sort:focus { outline: none; border-color: var(--accent); }

    /* Reset link */
    .filter-reset {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 7px 10px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        background: var(--surface); color: var(--ink-4);
        font-size: 12px; font-weight: 600; text-decoration: none;
        transition: background .15s, color .15s;
        white-space: nowrap;
    }

    .filter-reset:hover { background: var(--surface-3); color: var(--ink-3); }

    /* Result line */
    .result-line {
        font-size: 12px; color: var(--ink-4);
        margin-left: auto;
        white-space: nowrap;
    }

    .result-line strong { color: var(--ink-2); font-weight: 700; }
</style>
@endsection

@section('content')
<h1 class="page-title">Utilisateurs</h1>
<p class="page-subtitle">Gérez les comptes, rôles et accès de tous les utilisateurs.</p>

{{-- Filter strip --}}
<form method="GET" action="{{ route('admin.users.index') }}" id="filter-form">

    <div class="filter-strip">

        {{-- Search --}}
        <div class="filter-search-wrap">
            <i class="ti ti-search"></i>
            <input type="text" name="search" id="search-input" class="filter-search"
                   placeholder="Nom ou adresse e-mail…"
                   value="{{ request('search') }}">
        </div>

        {{-- Role tabs --}}
        <div class="role-tabs">
            @php
                $currentRole = request('role', '');
                $baseParams  = request()->except(['role', 'page']);
            @endphp

            <a href="{{ route('admin.users.index', array_merge($baseParams, ['role' => ''])) }}"
               class="role-tab {{ $currentRole === '' ? 'active-all' : '' }}">
                <i class="ti ti-users"></i> Tous
            </a>
            <a href="{{ route('admin.users.index', array_merge($baseParams, ['role' => 'student'])) }}"
               class="role-tab {{ $currentRole === 'student' ? 'active-student' : '' }}">
                <i class="ti ti-school"></i> Étudiants
            </a>
            <a href="{{ route('admin.users.index', array_merge($baseParams, ['role' => 'teacher'])) }}"
               class="role-tab {{ $currentRole === 'teacher' ? 'active-teacher' : '' }}">
                <i class="ti ti-chalkboard"></i> Enseignants
            </a>
            <a href="{{ route('admin.users.index', array_merge($baseParams, ['role' => 'admin'])) }}"
               class="role-tab {{ $currentRole === 'admin' ? 'active-admin' : '' }}">
                <i class="ti ti-shield"></i> Admins
            </a>
        </div>

        {{-- Sort --}}
        <select name="sort" id="sort-select" class="filter-sort">
            <option value="name"       {{ request('sort', 'name') === 'name'      ? 'selected' : '' }}>Trier : Nom A–Z</option>
            <option value="name_desc"  {{ request('sort') === 'name_desc'         ? 'selected' : '' }}>Trier : Nom Z–A</option>
            <option value="newest"     {{ request('sort') === 'newest'            ? 'selected' : '' }}>Trier : Plus récents</option>
            <option value="oldest"     {{ request('sort') === 'oldest'            ? 'selected' : '' }}>Trier : Plus anciens</option>
        </select>

        {{-- Submit button --}}
        <button type="submit" class="tb-btn tb-btn-primary" style="white-space:nowrap;">
            <i class="ti ti-search"></i> Filtrer
        </button>

        {{-- Reset --}}
        @if(request()->hasAny(['search', 'role', 'sort']))
            <a href="{{ route('admin.users.index') }}" class="filter-reset">
                <i class="ti ti-x" style="font-size:13px;"></i> Effacer
            </a>
        @endif

        {{-- Count --}}
        <span class="result-line"><strong>{{ $users->count() }}</strong> résultat(s)</span>

    </div>
</form>

<div class="card" style="overflow: hidden;">
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
                                <select name="role" onchange="this.form.submit()"
                                        style="padding:5px 28px 5px 10px; border-radius:var(--radius-sm); border:1px solid var(--line); font-size:12px; background:var(--surface); color:var(--ink-2); cursor:pointer; appearance:none; background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%239aa3af\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 6px center; background-size:14px;">
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
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
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
                            @if(request()->hasAny(['search', 'role']))
                                — <a href="{{ route('admin.users.index') }}" style="color:var(--accent);">effacer les filtres</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Debounce search: waits 400ms after the user stops typing before submitting
    const searchInput = document.getElementById('search-input');
    const sortSelect  = document.getElementById('sort-select');
    let debounceTimer;

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 400);
    });

    // Sort auto-submits immediately on change (single deliberate action, not typing)
    sortSelect.addEventListener('change', () => {
        document.getElementById('filter-form').submit();
    });
</script>
@endsection