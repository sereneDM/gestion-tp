<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Plateforme TP')</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="{{ asset('css/posts.css') }}">
@yield('extra-styles')
</head>

<body class="bg-[#0f172a] text-slate-200 min-h-screen">

{{-- TOP NAVBAR --}}
<nav class="fixed top-0 left-0 right-0 h-[72px] bg-[#0f172a] border-b border-slate-800 flex items-center px-6 gap-4 z-50">

    {{-- Logo --}}
    <div class="flex items-center gap-2 min-w-[44px]">
        <div class="w-9 h-9 rounded-lg bg-violet-600 flex items-center justify-center flex-shrink-0">
            <svg width="16" height="16" viewBox="0 0 14 14" fill="none">
                <rect x="1" y="1" width="4" height="4" rx="1" fill="white"/>
                <rect x="9" y="1" width="4" height="4" rx="1" fill="white"/>
                <rect x="1" y="9" width="4" height="4" rx="1" fill="white"/>
                <rect x="9" y="9" width="4" height="4" rx="1" fill="white"/>
            </svg>
        </div>
        <span class="text-white font-semibold text-sm hidden md:block">Plateforme TP</span>
    </div>

    {{-- Divider --}}
    <div class="w-px h-7 bg-slate-700"></div>

    {{-- Nav links --}}
    <div class="flex items-center gap-1 flex-1 overflow-x-auto hide-scrollbar">
        @if(Auth::user()->isTeacher())
            <a href="{{ route('feed.index') }}"
               class="nav-link {{ request()->routeIs('feed.*') ? 'nav-link-active' : '' }}">
                🏠 Fil d'actualité
            </a>
            <a href="{{ route('teacher.courses.index') }}"
               class="nav-link {{ request()->routeIs('teacher.courses.*') ? 'nav-link-active' : '' }}">
                📚 Mes cours
            </a>
            <a href="{{ route('teacher.progress.index') }}"
               class="nav-link {{ request()->routeIs('teacher.progress.*') ? 'nav-link-active' : '' }}">
                📊 Suivi
            </a>
            <a href="{{ route('teacher.attendance.index') }}"
               class="nav-link {{ request()->routeIs('teacher.attendance.*') ? 'nav-link-active' : '' }}">
                ✓ Présences
            </a>
            <a href="{{ route('teacher.statistics') }}"
               class="nav-link {{ request()->routeIs('teacher.statistics') ? 'nav-link-active' : '' }}">
                📈 Statistiques
            </a>
        @elseif(Auth::user()->isStudent())
            <a href="{{ route('feed.index') }}"
               class="nav-link {{ request()->routeIs('feed.*') ? 'nav-link-active' : '' }}">
                🏠 Accueil
            </a>
            <a href="{{ route('student.my-courses') }}"
               class="nav-link {{ request()->routeIs('student.my-courses') || request()->routeIs('student.courses.*') ? 'nav-link-active' : '' }}">
                📚 Mes cours
            </a>
            <a href="{{ route('student.submissions.index') }}"
               class="nav-link {{ request()->routeIs('student.submissions.*') ? 'nav-link-active' : '' }}">
                📄 Soumissions
            </a>
            <a href="{{ route('student.progress') }}"
               class="nav-link {{ request()->routeIs('student.progress') ? 'nav-link-active' : '' }}">
                📈 Progression
            </a>
        @elseif(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : '' }}">
                🏠 Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : '' }}">
                👥 Utilisateurs
            </a>
            <a href="{{ route('admin.classes.index') }}"
               class="nav-link {{ request()->routeIs('admin.classes.*') ? 'nav-link-active' : '' }}">
                🏫 Classes
            </a>
            <a href="{{ route('admin.statistics') }}"
               class="nav-link {{ request()->routeIs('admin.statistics') ? 'nav-link-active' : '' }}">
                📈 Statistiques
            </a>
            <a href="{{ route('admin.system-logs') }}"
               class="nav-link {{ request()->routeIs('admin.system-logs') ? 'nav-link-active' : '' }}">
                🖥️ Logs
            </a>
            <a href="{{ route('admin.settings.index') }}"
               class="nav-link {{ request()->routeIs('admin.settings.*') ? 'nav-link-active' : '' }}">
                ⚙️ Paramètres
            </a>
        @endif
    </div>

    {{-- Right side --}}
    <div class="flex items-center gap-3 flex-shrink-0">

        {{-- Notifications bell --}}
        @php
            $unreadNotifs = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
        @endphp
        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg hover:bg-slate-800 transition-colors">
            <svg width="20" height="20" viewBox="0 0 18 18" fill="none" class="text-slate-200">
                <path d="M9 1a5 5 0 0 0-5 5v3l-1.5 2.5h13L14 9V6a5 5 0 0 0-5-5z" stroke="currentColor" stroke-width="1.5"/>
                <path d="M7 14a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            @if($unreadNotifs > 0)
                <span class="absolute top-1 right-1 w-4 h-4 bg-violet-600 rounded-full text-white text-[9px] flex items-center justify-center font-bold">
                    {{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}
                </span>
            @endif
        </a>

        {{-- User dropdown --}}
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="flex items-center gap-2 cursor-pointer p-1.5 rounded-lg hover:bg-slate-800 transition-colors">
                <img src="{{ Auth::user()->profile_picture_url }}"
                     alt="{{ Auth::user()->name }}"
                     class="w-9 h-9 rounded-full object-cover flex-shrink-0"
                     style="background: transparent;"
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzY2N2VlYSIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjQyIiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9ImNlbnRyYWwiPj88L3RleHQ+PC9zdmc+'">
                <div class="hidden md:block text-left">
                    <div class="text-xs font-medium text-white leading-tight">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-slate-400 leading-tight">
                        @if(Auth::user()->isAdmin()) Administrateur
                        @elseif(Auth::user()->isTeacher()) Enseignant
                        @else Étudiant @endif
                    </div>
                </div>
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" class="text-slate-300 hidden md:block">
                    <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <ul tabindex="0" class="dropdown-content bg-[#1e293b] border border-slate-700 rounded-xl shadow-xl mt-2 w-48 p-1 z-50">
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-slate-200 hover:text-white hover:bg-slate-700 rounded-lg px-3 py-2 text-sm">
                        Mon profil
                    </a>
                </li>
                <div class="border-t border-slate-700 my-1"></div>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-red-400 hover:text-red-300 hover:bg-slate-700 rounded-lg px-3 py-2 text-sm w-full text-left cursor-pointer">
                            Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<main class="pt-[72px] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">

        {{-- Session toasts (rendered into the fixed toast container via JS) --}}
        @if(session('success'))
            <div class="toast-bar toast-success" data-toast>✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="toast-bar toast-error" data-toast>✗ {{ session('error') }}</div>
        @endif

        {{-- Page header / breadcrumbs --}}
        <div class="mb-6">
            @hasSection('breadcrumbs')
                @yield('breadcrumbs')
            @else
                @if(Breadcrumbs::exists())
                    {{ Breadcrumbs::render() }}
                @else
                    <h1 class="text-xl font-semibold text-white">@yield('page-title')</h1>
                @endif
            @endif
        </div>

        {{-- Content --}}
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 p-6">
            @yield('content')
        </div>

    </div>
</main>

{{-- Toast container (fixed, bottom-right, always visible regardless of scroll) --}}
<div id="toast-container" style="position:fixed; bottom:2rem; right:2rem; z-index:9999; display:flex; flex-direction:column; gap:0.75rem; align-items:flex-end; pointer-events:none;"></div>

{{-- Custom Confirm Modal --}}
<div id="confirm-modal" class="hidden fixed inset-0 bg-black/60 z-[9999] items-center justify-center">
    <div class="bg-[#1e293b] border border-slate-700 rounded-2xl p-8 max-w-sm w-[90%] text-center shadow-2xl" style="animation: popIn 0.2s ease;">
        <div id="confirm-icon" class="text-4xl mb-4">⚠️</div>
        <div id="confirm-message" class="text-slate-200 text-sm mb-6 leading-relaxed"></div>
        <div class="flex gap-3 justify-center">
            <button id="confirm-cancel" class="px-5 py-2 border border-slate-600 rounded-lg text-slate-200 hover:bg-slate-700 text-sm transition-colors">Annuler</button>
            <button id="confirm-ok" class="px-5 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white text-sm font-medium transition-colors">Confirmer</button>
        </div>
    </div>
</div>

<style>
@keyframes popIn { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }

.nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    color: #cbd5e1;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.15s;
}
.nav-link:hover { background: #1e293b; color: #e2e8f0; }
.nav-link-active { background: rgba(139,92,246,0.15); color: #a78bfa; }

.dropdown-content li form {
    display: block;
    width: 100%;
}

/* ── Unified toast styles ── */
.toast-bar {
    max-width: 320px;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-size: 0.875rem;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: auto;
}
.toast-success {
    background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.3);
    color: #86efac;
}
.toast-error {
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.3);
    color: #fca5a5;
}

.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Shared file upload styles */
.file-upload {
    border: 2px dashed #475569;
    padding: 1rem;
    text-align: center;
    border-radius: 0.75rem;
    background: #1e293b;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.9rem;
    color: #cbd5e1;
    display: block;
}
.file-upload:hover {
    background: #273548;
    border-color: #6366f1;
    color: #a5b4fc;
}
.file-hint {
    font-size: 0.8rem;
    margin-top: 0.25rem;
    color: #64748b;
}
.selected-file {
    margin-top: 0.5rem;
    padding: 0.4rem 0.75rem;
    background: rgba(34,197,94,0.1);
    border-left: 3px solid #22c55e;
    border-radius: 0.75rem;
    font-size: 0.85rem;
    color: #a7f3d0;
}
input[type="file"] {
    display: none;
}
</style>

<script>
/* ── Unified toast function — use this everywhere ── */
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast-bar toast-' + type;
    toast.textContent = message;
    container.appendChild(toast);
    requestAnimationFrame(() => toast.style.opacity = '1');
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('keydown', function(e){
    if(e.key === 'Enter' && e.target.tagName === 'INPUT' && e.target.type !== 'file'){
        const form = e.target.closest('form');
        if(form){ e.preventDefault(); form.submit(); }
    }
    if(e.key === 'Enter' && (e.ctrlKey || e.metaKey) && e.target.tagName === 'TEXTAREA'){
        const form = e.target.closest('form');
        if(form){ e.preventDefault(); form.submit(); }
    }
});

function customConfirm(message, icon){
    return new Promise((resolve) => {
        const modal     = document.getElementById('confirm-modal');
        const msgEl     = document.getElementById('confirm-message');
        const iconEl    = document.getElementById('confirm-icon');
        const okBtn     = document.getElementById('confirm-ok');
        const cancelBtn = document.getElementById('confirm-cancel');
        msgEl.textContent   = message;
        iconEl.textContent  = icon || '⚠️';
        modal.style.display = 'flex';
        const cleanup = () => modal.style.display = 'none';
        okBtn.onclick     = () => { cleanup(); resolve(true); };
        cancelBtn.onclick = () => { cleanup(); resolve(false); };
        modal.onclick     = (e) => { if(e.target === modal){ cleanup(); resolve(false); } };
    });
}

function pickIcon(msg){
    const m = msg.toLowerCase();
    if(m.includes('supprimer') || m.includes('irréversible')) return '🗑️';
    if(m.includes('quitter'))   return '🚪';
    if(m.includes('générer') || m.includes('nouveau code'))   return '🔄';
    if(m.includes('réinitialiser')) return '🔄';
    if(m.includes('rôle') || m.includes('role')) return '👤';
    return '⚠️';
}

async function handleRoleChange(select){
    const confirmed = await customConfirm('Changer le rôle de cet utilisateur?', '👤');
    if(confirmed){ select.closest('form').submit(); }
    else { select.value = select.dataset.original; }
}

document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('select[name="role"]').forEach(select => {
        select.dataset.original = select.value;
    });

    document.querySelectorAll('[onclick]').forEach(el => {
        const original = el.getAttribute('onclick');
        if(!original.includes('confirm(')) return;
        const match   = original.match(/confirm\(['"](.+?)['"]\)/);
        const message = match ? match[1] : 'Êtes-vous sûr?';
        const icon    = pickIcon(message);
        el.removeAttribute('onclick');
        el.addEventListener('click', async function(e){
            e.preventDefault();
            e.stopPropagation();
            const confirmed = await customConfirm(message, icon);
            if(confirmed){
                const form = el.closest('form') || el.form;
                if(form) form.submit();
                else if(el.tagName === 'A' && el.href) window.location = el.href;
            }
        });
    });

    document.querySelectorAll('form[onsubmit]').forEach(form => {
        const original = form.getAttribute('onsubmit');
        if(!original.includes('confirm(')) return;
        const match   = original.match(/confirm\(['"](.+?)['"]\)/);
        const message = match ? match[1] : 'Êtes-vous sûr?';
        const icon    = pickIcon(message);
        form.removeAttribute('onsubmit');
        form.addEventListener('submit', async function(e){
            e.preventDefault();
            const confirmed = await customConfirm(message, icon);
            if(confirmed) form.submit();
        });
    });

    document.querySelectorAll('input[type="email"]').forEach(input => {
        const feedback = document.createElement('div');
        feedback.style.cssText = 'font-size:0.8rem; margin-top:0.4rem; display:none;';
        input.parentNode.insertBefore(feedback, input.nextSibling);
        function validate() {
            const val = input.value.trim();
            if (!val) { feedback.style.display = 'none'; return; }
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(val);
            feedback.textContent = valid ? '✓ Email valide' : '✗ Adresse email invalide';
            feedback.style.color = valid ? '#86efac' : '#fca5a5';
            feedback.style.display = 'block';
        }
        input.addEventListener('input', validate);
        input.addEventListener('blur', validate);
    });

    // Move session toasts into the fixed container and animate them
    document.querySelectorAll('[data-toast]').forEach(toast => {
        toast.remove(); // remove from page flow
        document.getElementById('toast-container').appendChild(toast);
        requestAnimationFrame(() => toast.style.opacity = '1');
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    });
});

function showFileName(input, id) {
    const preview = document.getElementById(id + '-preview');
    if (input.files.length > 0) {
        preview.style.display = 'block';
        preview.innerHTML = '✓ Fichier sélectionné: ' + input.files[0].name;
    } else {
        preview.style.display = 'none';
    }
}
function showFileName(input, id) {
    const preview = document.getElementById(id + '-preview');
    if (preview && input.files && input.files[0]) {
        preview.style.display = 'block';
        preview.innerHTML = '✓ Fichier sélectionné: ' + input.files[0].name;
    } else if (preview) {
        preview.style.display = 'none';
    }
}
</script>

<script>
    // Clear flash messages on back/forward navigation
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            document.querySelectorAll('[data-toast]').forEach(el => el.remove());
        }
    });
</script>

@yield('extra-scripts')
</body>
</html>