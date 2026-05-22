<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Plateforme TP') — {{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="{{ asset('css/posts.css') }}">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">

@yield('extra-styles')
</head>

<body style="background:#f0f2f5; color:#0d1117; font-family:'DM Sans',sans-serif; min-height:100vh; margin:0;">

{{-- TOP NAVBAR --}}
<nav style="position:fixed; top:0; left:0; right:0; height:60px;
            background:#ffffff; border-bottom:1px solid #e8ebef;
            display:flex; align-items:center; padding:0 1.5rem; gap:1rem;
            z-index:50; box-shadow:0 1px 3px rgba(0,0,0,0.06);">

    {{-- Logo --}}
    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('feed.index') }}"
       style="display:flex; align-items:center; gap:10px; text-decoration:none; flex-shrink:0;">
        <div style="width:34px; height:34px; border-radius:9px; background:#3d5afe;
                    display:flex; align-items:center; justify-content:center;">
            <svg width="16" height="16" viewBox="0 0 14 14" fill="none">
                <rect x="1" y="1" width="4" height="4" rx="1" fill="white"/>
                <rect x="9" y="1" width="4" height="4" rx="1" fill="white"/>
                <rect x="1" y="9" width="4" height="4" rx="1" fill="white"/>
                <rect x="9" y="9" width="4" height="4" rx="1" fill="white"/>
            </svg>
        </div>
        <span style="font-size:0.88rem; font-weight:700; color:#0d1117; letter-spacing:-0.01em; display:inline-block; max-width:220px; white-space:normal; word-break:break-word;">{{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}</span>
    </a>

    {{-- Divider --}}
    <div style="width:1px; height:24px; background:#e8ebef; flex-shrink:0;"></div>

    {{-- Nav links --}}
    <div style="display:flex; align-items:center; gap:2px; flex:1; overflow-x:auto;" class="hide-scrollbar">
        @if(Auth::user()->isTeacher())
            <a href="{{ route('feed.index') }}" class="nav-link {{ request()->routeIs('feed.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-home"></i> Fil d'actualité
            </a>
            <a href="{{ route('teacher.courses.index') }}" class="nav-link {{ request()->routeIs('teacher.courses.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-books"></i> Mes cours
            </a>
            <a href="{{ route('teacher.progress.index') }}" class="nav-link {{ request()->routeIs('teacher.progress.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-chart-bar"></i> Suivi
            </a>
            <a href="{{ route('teacher.attendance.index') }}" class="nav-link {{ request()->routeIs('teacher.attendance.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-clipboard-check"></i> Présences
            </a>
            <a href="{{ route('teacher.statistics') }}" class="nav-link {{ request()->routeIs('teacher.statistics') ? 'nav-link-active' : '' }}">
                <i class="ti ti-chart-line"></i> Statistiques
            </a>
        @elseif(Auth::user()->isStudent())
            <a href="{{ route('feed.index') }}" class="nav-link {{ request()->routeIs('feed.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-home"></i> Accueil
            </a>
            <a href="{{ route('student.my-courses') }}" class="nav-link {{ request()->routeIs('student.my-courses') || request()->routeIs('student.courses.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-books"></i> Mes cours
            </a>
            <a href="{{ route('student.submissions.index') }}" class="nav-link {{ request()->routeIs('student.submissions.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-file-text"></i> Soumissions
            </a>
            <a href="{{ route('student.progress') }}" class="nav-link {{ request()->routeIs('student.progress') ? 'nav-link-active' : '' }}">
                <i class="ti ti-trending-up"></i> Progression
            </a>
        @elseif(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">
                <i class="ti ti-layout-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-users"></i> Utilisateurs
            </a>
            <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-building"></i> Classes
            </a>
            <a href="{{ route('admin.statistics') }}" class="nav-link {{ request()->routeIs('admin.statistics') ? 'nav-link-active' : '' }}">
                <i class="ti ti-chart-line"></i> Statistiques
            </a>
            <a href="{{ route('admin.system-logs') }}" class="nav-link {{ request()->routeIs('admin.system-logs') ? 'nav-link-active' : '' }}">
                <i class="ti ti-terminal"></i> Logs
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'nav-link-active' : '' }}">
                <i class="ti ti-settings"></i> Paramètres
            </a>
        @endif
    </div>

    {{-- Right side --}}
    <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0;">

        {{-- Notifications --}}
        @php
            $unreadNotifs = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
        @endphp
        <a href="{{ route('notifications.index') }}"
           style="position:relative; width:36px; height:36px; border-radius:8px;
                  display:flex; align-items:center; justify-content:center;
                  color:#6b7585; text-decoration:none; transition:background 0.15s;"
           onmouseover="this.style.background='#f0f2f5'"
           onmouseout="this.style.background='transparent'">
            <i class="ti ti-bell" style="font-size:19px;"></i>
            @if($unreadNotifs > 0)
                <span style="position:absolute; top:5px; right:5px; width:8px; height:8px;
                             background:#3d5afe; border-radius:50%; border:2px solid white;"></span>
            @endif
        </a>

        {{-- User dropdown --}}
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button"
     style="display:flex; align-items:center; gap:8px; cursor:pointer;
            padding:5px 10px 5px 5px; border-radius:10px;
            background:transparent; transition:background 0.15s;"
     onmouseover="this.style.background='#f0f2f5'"
     onmouseout="this.style.background='transparent'">
                <img src="{{ Auth::user()->profile_picture_url }}"
                     alt="{{ Auth::user()->name }}"
                     style="width:30px; height:30px; border-radius:50%; object-fit:cover; flex-shrink:0;"
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzNkNWFmZSIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjQyIiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9ImNlbnRyYWwiPj88L3RleHQ+PC9zdmc+'">
                <div style="display:none;" class="md-show">
                    <div style="font-size:0.78rem; font-weight:600; color:#0d1117; line-height:1.2;">{{ Auth::user()->name }}</div>
                    <div style="font-size:0.68rem; color:#9aa3af; line-height:1.2;">
                        @if(Auth::user()->isAdmin()) Administrateur
                        @elseif(Auth::user()->isTeacher()) Enseignant
                        @else Étudiant @endif
                    </div>
                </div>
                <i class="ti ti-chevron-down" style="font-size:13px; color:#9aa3af;"></i>
            </div>

            <ul tabindex="0"
                style="position:absolute; right:0; top:calc(100% + 6px);
                       background:#ffffff; border:1px solid #e8ebef; border-radius:12px;
                       box-shadow:0 8px 24px rgba(0,0,0,0.1); min-width:180px;
                       padding:0.4rem; list-style:none; z-index:999;"
                class="dropdown-content">
                <li>
                    <a href="{{ route('profile.edit') }}"
                       style="display:flex; align-items:center; gap:8px; padding:0.5rem 0.75rem;
                              border-radius:8px; text-decoration:none; font-size:0.85rem;
                              color:#3d4550; transition:background 0.15s;"
                       onmouseover="this.style.background='#f0f2f5'"
                       onmouseout="this.style.background='transparent'">
                        <i class="ti ti-user" style="font-size:15px; color:#9aa3af;"></i>
                        Mon profil
                    </a>
                </li>
                <li style="border-top:1px solid #e8ebef; margin:0.3rem 0;"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                style="display:flex; align-items:center; gap:8px; padding:0.5rem 0.75rem;
                                       border-radius:8px; font-size:0.85rem; color:#e53935;
                                       background:none; border:none; cursor:pointer; width:100%;
                                       text-align:left; font-family:inherit; transition:background 0.15s;"
                                onmouseover="this.style.background='#fff0f0'"
                                onmouseout="this.style.background='transparent'">
                            <i class="ti ti-logout" style="font-size:15px;"></i>
                            Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<main style="padding-top:60px; min-height:100vh;">
    <div style="max-width:1200px; margin:0 auto; padding:1.75rem 1.25rem;">

        @if(session('success'))
            <div class="toast-bar toast-success" data-toast>
                <i class="ti ti-circle-check"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="toast-bar toast-error" data-toast>
                <i class="ti ti-circle-x"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Breadcrumbs --}}
        <div style="margin-bottom:1.25rem;">
            @hasSection('breadcrumbs')
                @yield('breadcrumbs')
            @else
                @if(Breadcrumbs::exists())
                    {{ Breadcrumbs::render() }}
                @else
                    <h1 style="font-size:1.25rem; font-weight:700; color:#0d1117;">@yield('page-title')</h1>
                @endif
            @endif
        </div>

        {{-- Content wrapper — transparent so each page controls its own bg --}}
        @yield('content')

    </div>
</main>

{{-- Toast container --}}
<div id="toast-container"
     style="position:fixed; bottom:2rem; right:2rem; z-index:9999;
            display:flex; flex-direction:column; gap:0.6rem;
            align-items:flex-end; pointer-events:none;"></div>

{{-- Confirm modal --}}
<div id="confirm-modal"
     style="display:none; position:fixed; inset:0; background:rgba(13,17,23,0.45);
            backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#ffffff; border:1px solid #e8ebef; border-radius:18px;
                padding:2rem; max-width:380px; width:90%; text-align:center;
                box-shadow:0 20px 60px rgba(0,0,0,0.12); animation:popIn 0.2s ease;">
        <div id="confirm-icon"
             style="width:52px; height:52px; border-radius:14px; background:#fff0f0;
                    display:flex; align-items:center; justify-content:center;
                    margin:0 auto 1rem; font-size:24px; color:#e53935;">
            <i class="ti ti-alert-triangle"></i>
        </div>
        <div id="confirm-message"
             style="color:#3d4550; font-size:0.9rem; margin-bottom:1.5rem; line-height:1.6;"></div>
        <div style="display:flex; gap:0.6rem; justify-content:center;">
            <button id="confirm-cancel"
                    style="padding:0.55rem 1.2rem; border:1px solid #e8ebef; border-radius:9px;
                           background:#ffffff; color:#6b7585; font-size:0.875rem; cursor:pointer;
                           font-family:inherit; transition:background 0.15s;"
                    onmouseover="this.style.background='#f0f2f5'"
                    onmouseout="this.style.background='#ffffff'">
                Annuler
            </button>
            <button id="confirm-ok"
                    style="padding:0.55rem 1.2rem; border:none; border-radius:9px;
                           background:#e53935; color:#ffffff; font-size:0.875rem;
                           font-weight:600; cursor:pointer; font-family:inherit; transition:background 0.15s;"
                    onmouseover="this.style.background='#c62828'"
                    onmouseout="this.style.background='#e53935'">
                Confirmer
            </button>
        </div>
    </div>
</div>

<style>
@keyframes popIn {
    from { transform: scale(0.88); opacity: 0; }
    to   { transform: scale(1);    opacity: 1; }
}

/* ── Nav links ── */
.nav-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 11px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 500;
    color: #6b7585;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.15s, color 0.15s;
}
.nav-link:hover { background: #f0f2f5; color: #0d1117; }
.nav-link i { font-size: 15px; }
.nav-link-active {
    background: #eef1ff;
    color: #3d5afe;
    font-weight: 600;
}
.nav-link-active:hover { background: #e4e9ff; color: #3d5afe; }

/* ── Toasts ── */
.toast-bar {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    max-width: 340px;
    padding: 0.7rem 1rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-family: 'DM Sans', sans-serif;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: auto;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}
.toast-success {
    background: #ecfdf5;
    border: 1px solid rgba(16,185,129,0.25);
    color: #065f46;
}
.toast-error {
    background: #fff0f0;
    border: 1px solid rgba(229,57,53,0.25);
    color: #991b1b;
}

/* ── Scrollbar ── */
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* ── File upload component ── */
.file-upload {
    border: 1.5px dashed #d1d6dd;
    padding: 1rem;
    text-align: center;
    border-radius: 10px;
    background: #f5f6f8;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.875rem;
    color: #6b7585;
    display: block;
}
.file-upload:hover {
    background: #eef1ff;
    border-color: #3d5afe;
    color: #3d5afe;
}
.file-hint {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    color: #9aa3af;
}
.selected-file {
    margin-top: 0.5rem;
    padding: 0.4rem 0.75rem;
    background: #ecfdf5;
    border-left: 3px solid #10b981;
    border-radius: 6px;
    font-size: 0.82rem;
    color: #065f46;
}
input[type="file"] { display: none; }
#profile_picture { display: block !important; }

/* ── Breadcrumb ── */
.breadcrumb { background: transparent; padding: 0; margin-bottom: 0; list-style: none; display: flex; flex-wrap: wrap; gap: 4px; }
.breadcrumb-item { color: #9aa3af; font-size: 0.82rem; display: flex; align-items: center; gap: 4px; }
.breadcrumb-item a { color: #6b7585; text-decoration: none; transition: color 0.15s; }
.breadcrumb-item a:hover { color: #0d1117; }
.breadcrumb-item.active { color: #3d4550; font-weight: 600; }
.breadcrumb-item + .breadcrumb-item::before { content: "/"; color: #d1d6dd; }

/* ── Dropdown fix for DaisyUI ── */
.dropdown-content { position: absolute !important; }
.dropdown-content li form { display: block; width: 100%; }

/* ── Responsive md-show ── */
@media (min-width: 768px) {
    .md-show { display: block !important; }
}
</style>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast     = document.createElement('div');
    toast.className = 'toast-bar toast-' + type;
    const icon = type === 'success'
        ? '<i class="ti ti-circle-check"></i>'
        : '<i class="ti ti-circle-x"></i>';
    toast.innerHTML = icon + ' ' + message;
    container.appendChild(toast);
    requestAnimationFrame(() => toast.style.opacity = '1');
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.tagName === 'INPUT' && e.target.type !== 'file') {
        const form = e.target.closest('form');
        if (form) { e.preventDefault(); form.submit(); }
    }
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey) && e.target.tagName === 'TEXTAREA') {
        const form = e.target.closest('form');
        if (form) { e.preventDefault(); form.submit(); }
    }
});

function customConfirm(message, icon) {
    return new Promise((resolve) => {
        const modal     = document.getElementById('confirm-modal');
        const msgEl     = document.getElementById('confirm-message');
        const iconEl    = document.getElementById('confirm-icon');
        const okBtn     = document.getElementById('confirm-ok');
        const cancelBtn = document.getElementById('confirm-cancel');

        msgEl.textContent    = message;
        iconEl.innerHTML     = icon || '<i class="ti ti-alert-triangle"></i>';
        modal.style.display  = 'flex';

        const cleanup = () => { modal.style.display = 'none'; };
        okBtn.onclick     = () => { cleanup(); resolve(true); };
        cancelBtn.onclick = () => { cleanup(); resolve(false); };
        modal.onclick     = (e) => { if (e.target === modal) { cleanup(); resolve(false); } };
    });
}

function pickIcon(msg) {
    const m = msg.toLowerCase();
    if (m.includes('supprimer') || m.includes('irréversible'))
        return '<i class="ti ti-trash" style="color:#e53935"></i>';
    if (m.includes('quitter'))
        return '<i class="ti ti-door-exit" style="color:#e53935"></i>';
    if (m.includes('générer') || m.includes('nouveau code') || m.includes('réinitialiser'))
        return '<i class="ti ti-refresh" style="color:#f59e0b"></i>';
    if (m.includes('rôle') || m.includes('role'))
        return '<i class="ti ti-user-cog" style="color:#3d5afe"></i>';
    return '<i class="ti ti-alert-triangle" style="color:#e53935"></i>';
}

async function handleRoleChange(select) {
    const confirmed = await customConfirm('Changer le rôle de cet utilisateur ?',
        '<i class="ti ti-user-cog" style="color:#3d5afe"></i>');
    if (confirmed) select.closest('form').submit();
    else select.value = select.dataset.original;
}

document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('select[name="role"]').forEach(select => {
        select.dataset.original = select.value;
    });

    document.querySelectorAll('[onclick]').forEach(el => {
        const original = el.getAttribute('onclick');
        if (!original.includes('confirm(')) return;
        const match   = original.match(/confirm\(['"](.+?)['"]\)/);
        const message = match ? match[1] : 'Êtes-vous sûr ?';
        const icon    = pickIcon(message);
        el.removeAttribute('onclick');
        el.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            const confirmed = await customConfirm(message, icon);
            if (confirmed) {
                const form = el.closest('form') || el.form;
                if (form) form.submit();
                else if (el.tagName === 'A' && el.href) window.location = el.href;
            }
        });
    });

    document.querySelectorAll('form[onsubmit]').forEach(form => {
        const original = form.getAttribute('onsubmit');
        if (!original.includes('confirm(')) return;
        const match   = original.match(/confirm\(['"](.+?)['"]\)/);
        const message = match ? match[1] : 'Êtes-vous sûr ?';
        const icon    = pickIcon(message);
        form.removeAttribute('onsubmit');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const confirmed = await customConfirm(message, icon);
            if (confirmed) form.submit();
        });
    });

    document.querySelectorAll('input[type="email"]').forEach(input => {
        const feedback = document.createElement('div');
        feedback.style.cssText = 'font-size:0.75rem; margin-top:0.35rem; display:none;';
        input.parentNode.insertBefore(feedback, input.nextSibling);
        function validate() {
            const val   = input.value.trim();
            if (!val) { feedback.style.display = 'none'; return; }
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(val);
            feedback.innerHTML = valid
                ? '<i class="ti ti-circle-check"></i> Email valide'
                : '<i class="ti ti-circle-x"></i> Adresse email invalide';
            feedback.style.color   = valid ? '#10b981' : '#e53935';
            feedback.style.display = 'block';
        }
        input.addEventListener('input', validate);
        input.addEventListener('blur',  validate);
    });

    // Move session toasts into fixed container
    document.querySelectorAll('[data-toast]').forEach(toast => {
        toast.remove();
        document.getElementById('toast-container').appendChild(toast);
        requestAnimationFrame(() => toast.style.opacity = '1');
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    });
});

function showFileName(input, id) {
    const preview = document.getElementById(id + '-preview');
    if (preview && input.files && input.files[0]) {
        preview.style.display = 'block';
        preview.innerHTML = '<i class="ti ti-circle-check"></i> ' + input.files[0].name;
    } else if (preview) {
        preview.style.display = 'none';
    }
}

window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        document.querySelectorAll('[data-toast]').forEach(el => el.remove());
    }
});
</script>

@yield('extra-scripts')
</body>
</html>