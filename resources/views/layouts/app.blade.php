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
{{-- ⚡ Apply saved theme BEFORE any CSS loads — eliminates all flash/lag --}}
<script>(function(){var t=localStorage.getItem('tp-theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="{{ asset('css/posts.css') }}">
@yield('extra-styles')
</head>

<body class="tp-body min-h-screen">

{{-- TOP NAVBAR --}}
<<<<<<< HEAD
<nav class="fixed top-0 left-0 right-0 h-[72px] bg-[#0f172a] border-b border-slate-800 flex items-center px-6 gap-4 z-50">
=======
<nav class="tp-navbar fixed top-0 left-0 right-0 h-[72px] flex items-center px-6 gap-4 z-50">
>>>>>>> 29f2233 (fifth update)

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
        <span class="font-semibold text-sm hidden md:block" style="color: var(--tp-nav-text-hover)">Plateforme TP</span>
    </div>

    {{-- Divider --}}
<<<<<<< HEAD
    <div class="w-px h-7 bg-slate-700"></div>
=======
    <div class="w-px h-7" style="background: var(--tp-border)"></div>
>>>>>>> 29f2233 (fifth update)

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

        {{-- Theme toggle --}}
        <button id="theme-toggle" onclick="toggleTheme()"
                class="p-2 rounded-lg transition-colors"
                style="background: transparent; border: none; cursor: pointer; color: var(--tp-text-secondary);"
                title="Changer le thème">
            <span id="theme-icon" class="text-lg">☀️</span>
        </button>

        {{-- Notifications bell --}}
        @php
            $unreadNotifs = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
        @endphp
<<<<<<< HEAD
        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg hover:bg-slate-800 transition-colors">
            <svg width="20" height="20" viewBox="0 0 18 18" fill="none" class="text-slate-200">
=======
        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg transition-colors" style="color: var(--tp-text-secondary);">
            <svg width="20" height="20" viewBox="0 0 18 18" fill="none">
>>>>>>> 29f2233 (fifth update)
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
            <div tabindex="0" role="button" class="flex items-center gap-2 cursor-pointer p-1.5 rounded-lg transition-colors tp-hover-bg">
                <img src="{{ Auth::user()->profile_picture_url }}"
                     alt="{{ Auth::user()->name }}"
                     class="w-9 h-9 rounded-full object-cover flex-shrink-0"
                     style="background: transparent;"
                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzY2N2VlYSIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjQyIiBmaWxsPSJ3aGl0ZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9ImNlbnRyYWwiPj88L3RleHQ+PC9zdmc+'">
                <div class="hidden md:block text-left">
<<<<<<< HEAD
                    <div class="text-xs font-medium text-white leading-tight">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-slate-400 leading-tight">
=======
                    <div class="text-xs font-medium leading-tight" style="color: var(--tp-nav-text-hover)">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] leading-tight" style="color: var(--tp-nav-text)">
>>>>>>> 29f2233 (fifth update)
                        @if(Auth::user()->isAdmin()) Administrateur
                        @elseif(Auth::user()->isTeacher()) Enseignant
                        @else Étudiant @endif
                    </div>
                </div>
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" class="hidden md:block" style="color: var(--tp-text-muted)">
                    <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
<<<<<<< HEAD
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
=======
            <ul tabindex="0" class="dropdown-content tp-dropdown rounded-xl shadow-xl mt-2 w-48 p-1 z-50">
                <li>
                    <a href="{{ route('profile.edit') }}" class="tp-dropdown-item flex items-center gap-2 rounded-lg px-3 py-2 text-sm">
                        Mon profil
                    </a>
                </li>
                <div class="tp-divider my-1"></div>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="tp-dropdown-item-danger flex items-center gap-2 rounded-lg px-3 py-2 text-sm w-full text-left cursor-pointer">
>>>>>>> 29f2233 (fifth update)
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

<<<<<<< HEAD
        {{-- Session toasts (rendered into the fixed toast container via JS) --}}
=======
        {{-- Session toasts --}}
>>>>>>> 29f2233 (fifth update)
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
                    <h1 class="text-xl font-semibold" style="color: var(--tp-page-title)">@yield('page-title')</h1>
                @endif
            @endif
        </div>

        {{-- Content --}}
        <div class="tp-content-card rounded-2xl p-6">
            @yield('content')
        </div>

    </div>
</main>

<<<<<<< HEAD
{{-- Toast container (fixed, bottom-right, always visible regardless of scroll) --}}
=======
{{-- Toast container --}}
>>>>>>> 29f2233 (fifth update)
<div id="toast-container" style="position:fixed; bottom:2rem; right:2rem; z-index:9999; display:flex; flex-direction:column; gap:0.75rem; align-items:flex-end; pointer-events:none;"></div>

{{-- Custom Confirm Modal --}}
<div id="confirm-modal" class="hidden fixed inset-0 bg-black/60 z-[9999] items-center justify-center">
    <div class="tp-modal rounded-2xl p-8 max-w-sm w-[90%] text-center shadow-2xl" style="animation: popIn 0.2s ease;">
        <div id="confirm-icon" class="text-4xl mb-4">⚠️</div>
        <div id="confirm-message" class="text-sm mb-6 leading-relaxed" style="color: var(--tp-text-secondary)"></div>
        <div class="flex gap-3 justify-center">
            <button id="confirm-cancel" class="tp-btn-cancel px-5 py-2 rounded-lg text-sm transition-colors">Annuler</button>
            <button id="confirm-ok" class="px-5 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white text-sm font-medium transition-colors">Confirmer</button>
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════
   CSS CUSTOM PROPERTIES — Theme variables
   NOTE: both themes defined explicitly on
   [data-theme] so the inline <script> in
   <head> sets the attribute before CSS loads,
   giving zero flash on either theme.
   ═══════════════════════════════════════════ */
[data-theme="dark"] {
    --tp-bg-base:        #0f172a;
    --tp-bg-surface:     #1e293b;
    --tp-bg-raised:      #0f172a;
    --tp-border:         #334155;
    --tp-border-hover:   #475569;
    --tp-text-primary:   #f1f5f9;
    --tp-text-secondary: #cbd5e1;
    --tp-text-muted:     #94a3b8;
    --tp-text-faint:     #64748b;
    --tp-input-bg:       #1e293b;
    --tp-input-border:   #475569;
    --tp-hover-bg:       rgba(255,255,255,0.05);
    --tp-table-header:   #334155;
    --tp-table-row-hover:#1e293b;
    --tp-accent:         #4f46e5;
    --tp-accent-hover:   #4338ca;
    --tp-accent-text:    #a78bfa;
    --tp-code-bg:        #0f172a;
    /* Nav & title specific */
    --tp-nav-text:       #cbd5e1;
    --tp-nav-text-hover: #f1f5f9;
    --tp-page-title:     #f1f5f9;
}

[data-theme="light"] {
    --tp-bg-base:        #f1f5f9;
    --tp-bg-surface:     #ffffff;
    --tp-bg-raised:      #f8fafc;
    --tp-border:         #e2e8f0;
    --tp-border-hover:   #cbd5e1;
    --tp-text-primary:   #0f172a;
    --tp-text-secondary: #334155;
    --tp-text-muted:     #64748b;
    --tp-text-faint:     #94a3b8;
    --tp-input-bg:       #ffffff;
    --tp-input-border:   #cbd5e1;
    --tp-hover-bg:       rgba(0,0,0,0.06);
    --tp-table-header:   #f1f5f9;
    --tp-table-row-hover:#f8fafc;
    --tp-accent:         #4f46e5;
    --tp-accent-hover:   #4338ca;
    --tp-accent-text:    #4f46e5;
    --tp-code-bg:        #f1f5f9;
    /* Nav & title specific — must be dark enough on white navbar */
    --tp-nav-text:       #1e293b;
    --tp-nav-text-hover: #0f172a;
    --tp-page-title:     #0f172a;
}

/* ═══════════════════════════════════════════
   Base layout
   ═══════════════════════════════════════════ */
.tp-body {
    background: var(--tp-bg-base);
    color: var(--tp-text-secondary);
    transition: background 0.2s, color 0.2s;
}

.tp-navbar {
    background: var(--tp-bg-base);
    border-bottom: 1px solid var(--tp-border);
}

.tp-content-card {
    background: var(--tp-bg-surface);
    border: 1px solid var(--tp-border);
}

.tp-hover-bg:hover {
    background: var(--tp-hover-bg);
}

/* ═══════════════════════════════════════════
   Nav links
   ═══════════════════════════════════════════ */
.nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
<<<<<<< HEAD
    color: #cbd5e1;
=======
    color: var(--tp-nav-text);
>>>>>>> 29f2233 (fifth update)
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.15s;
}
.nav-link:hover {
    background: var(--tp-hover-bg);
    color: var(--tp-nav-text-hover);
}
.nav-link-active {
    background: rgba(139,92,246,0.15);
    color: var(--tp-accent-text);
}
[data-theme="light"] .nav-link-active {
    background: rgba(79,70,229,0.1);
    color: #4338ca;
    font-weight: 600;
}

/* ═══════════════════════════════════════════
   Dropdown
   ═══════════════════════════════════════════ */
.tp-dropdown {
    background: var(--tp-bg-surface);
    border: 1px solid var(--tp-border);
}
.tp-dropdown-item {
    color: var(--tp-text-secondary);
    text-decoration: none;
    display: block;
    transition: background 0.15s, color 0.15s;
}
<<<<<<< HEAD

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
=======
.tp-dropdown-item:hover {
    background: var(--tp-hover-bg);
    color: var(--tp-text-primary);
}
.tp-dropdown-item-danger {
    color: #f87171;
    background: transparent;
    border: none;
    transition: background 0.15s;
}
.tp-dropdown-item-danger:hover {
    background: var(--tp-hover-bg);
    color: #fca5a5;
}
.tp-divider {
    border-top: 1px solid var(--tp-border);
}
.dropdown-content li form { display: block; width: 100%; }

/* ═══════════════════════════════════════════
   Toast
   ═══════════════════════════════════════════ */
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
    background: rgba(34,197,94,0.12);
    border: 1px solid rgba(34,197,94,0.3);
    color: #16a34a;
}
[data-theme="dark"] .toast-success { color: #86efac; }
.toast-error {
    background: rgba(239,68,68,0.12);
    border: 1px solid rgba(239,68,68,0.3);
    color: #dc2626;
}
[data-theme="dark"] .toast-error { color: #fca5a5; }

/* ═══════════════════════════════════════════
   Modal
   ═══════════════════════════════════════════ */
.tp-modal {
    background: var(--tp-bg-surface);
    border: 1px solid var(--tp-border);
}
.tp-btn-cancel {
    border: 1px solid var(--tp-border);
    color: var(--tp-text-secondary);
    background: transparent;
    cursor: pointer;
}
.tp-btn-cancel:hover { background: var(--tp-hover-bg); }

/* ═══════════════════════════════════════════
   Shared form elements — used across all pages
   ═══════════════════════════════════════════ */
.tp-card {
    background: var(--tp-bg-raised);
    border: 1px solid var(--tp-border);
    border-radius: 1rem;
}
.tp-card-surface {
    background: var(--tp-bg-surface);
    border: 1px solid var(--tp-border);
    border-radius: 1rem;
>>>>>>> 29f2233 (fifth update)
}

/* Labels */
.tp-label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--tp-text-secondary);
    font-weight: bold;
}

/* Inputs / Textarea / Select */
.tp-input,
.tp-textarea,
.tp-select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--tp-input-border);
    border-radius: 0.75rem;
    font-size: 1rem;
    background: var(--tp-input-bg);
    color: var(--tp-text-primary);
    box-sizing: border-box;
    transition: border-color 0.15s;
}
.tp-input::placeholder,
.tp-textarea::placeholder { color: var(--tp-text-faint); }
.tp-input:focus,
.tp-textarea:focus,
.tp-select:focus {
    outline: none;
    border-color: #6366f1;
}
.tp-textarea { min-height: 120px; resize: vertical; }
.tp-select option { background: var(--tp-input-bg); color: var(--tp-text-primary); }

/* Buttons */
.tp-btn {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 0.75rem;
    cursor: pointer;
    text-decoration: none;
    font-size: 0.9rem;
    display: inline-block;
    transition: opacity 0.15s;
}
.tp-btn:hover { opacity: 0.9; }
.tp-btn-primary { background: var(--tp-accent); color: white; }
.tp-btn-primary:hover { background: var(--tp-accent-hover); opacity: 1; }
.tp-btn-secondary { background: var(--tp-table-header); color: var(--tp-text-secondary); }
.tp-btn-secondary:hover { background: var(--tp-border-hover); opacity: 1; }
.tp-btn-info { background: #2563eb; color: white; }
.tp-btn-warning { background: #f59e0b; color: #1f2937; }
.tp-btn-danger { background: #dc2626; color: white; }
.tp-btn-sm { padding: 0.4rem 0.8rem; font-size: 0.85rem; }
.tp-btn-lg { padding: 0.75rem 1.5rem; font-size: 1rem; }
.tp-btn-full { width: 100%; text-align: center; }

/* Error text */
.tp-error { color: #f87171; font-size: 0.875rem; margin-top: 0.25rem; }
[data-theme="dark"] .tp-error { color: #fca5a5; }

/* Info box */
.tp-info-box {
    background: var(--tp-bg-raised);
    border-left: 4px solid var(--tp-accent);
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.75rem;
    color: var(--tp-text-secondary);
}

/* Tables */
.tp-table { width: 100%; border-collapse: collapse; }
.tp-table th,
.tp-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--tp-border);
    color: var(--tp-text-secondary);
}
.tp-table th {
    background: var(--tp-table-header);
    font-weight: bold;
    color: var(--tp-text-primary);
}
.tp-table tbody tr:hover { background: var(--tp-table-row-hover); }

/* Section headings inside cards */
.tp-section-title {
    color: var(--tp-accent-text);
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--tp-border);
}

/* Stat cards */
.tp-stat-card {
    background: var(--tp-bg-raised);
    padding: 1.5rem;
    border-radius: 1rem;
    text-align: center;
    border: 1px solid var(--tp-border);
}
.tp-stat-number { font-size: 2rem; font-weight: bold; color: #818cf8; }
.tp-stat-label { color: var(--tp-text-muted); margin-top: 0.5rem; font-size: 0.9rem; }

/* Status badges */
.tp-badge { display: inline-block; padding: 0.3rem 0.8rem; border-radius: 9999px; font-size: 0.8rem; font-weight: bold; }
.tp-badge-green  { background: rgba(34,197,94,0.15);  color: #16a34a; }
.tp-badge-red    { background: rgba(239,68,68,0.15);  color: #dc2626; }
.tp-badge-yellow { background: rgba(251,191,36,0.15); color: #d97706; }
.tp-badge-blue   { background: rgba(59,130,246,0.15); color: #2563eb; }
.tp-badge-purple { background: rgba(139,92,246,0.15); color: #7c3aed; }
[data-theme="dark"] .tp-badge-green  { color: #86efac; }
[data-theme="dark"] .tp-badge-red    { color: #fca5a5; }
[data-theme="dark"] .tp-badge-yellow { color: #facc15; }
[data-theme="dark"] .tp-badge-blue   { color: #5eead4; }
[data-theme="dark"] .tp-badge-purple { color: #a78bfa; }

/* File upload */
.file-upload {
    border: 2px dashed var(--tp-input-border);
    padding: 1rem;
    text-align: center;
    border-radius: 0.75rem;
    background: var(--tp-input-bg);
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.9rem;
    color: var(--tp-text-secondary);
    display: block;
}
.file-upload:hover {
    border-color: #6366f1;
    color: #818cf8;
}
.file-hint { font-size: 0.8rem; margin-top: 0.25rem; color: var(--tp-text-faint); }
.selected-file {
    margin-top: 0.5rem;
    padding: 0.4rem 0.75rem;
    background: rgba(34,197,94,0.1);
    border-left: 3px solid #22c55e;
    border-radius: 0.75rem;
    font-size: 0.85rem;
    color: #16a34a;
}
[data-theme="dark"] .selected-file { color: #a7f3d0; }
input[type="file"] { display: none; }

/* Char counter */
.char-counter { text-align: right; font-size: 0.78rem; margin-top: 0.25rem; color: var(--tp-text-faint); transition: color 0.2s; }
.char-counter.warning { color: #f59e0b; }
.char-counter.danger  { color: #ef4444; }

/* Scrollbar */
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Animations */
@keyframes popIn { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }

/* ═══════════════════════════════════════════
   Pagination
   ═══════════════════════════════════════════ */
.pagination { display: flex; justify-content: center; margin-top: 1.5rem; gap: 0.25rem; }
.page-link {
    color: var(--tp-text-secondary);
    background: var(--tp-bg-raised);
    border: 1px solid var(--tp-border);
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    text-decoration: none;
    transition: all 0.2s;
}
.page-link:hover { background: var(--tp-bg-surface); color: var(--tp-text-primary); }
.page-item.active .page-link { background: var(--tp-accent); color: white; border-color: var(--tp-accent); }
.page-item.disabled .page-link { color: var(--tp-text-faint); cursor: not-allowed; }
</style>

<script>
<<<<<<< HEAD
/* ── Unified toast function — use this everywhere ── */
=======
/* ── Theme toggle ── */
function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme');
    const next    = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('tp-theme', next);
    document.getElementById('theme-icon').textContent = next === 'dark' ? '☀️' : '🌙';
}

/* ── Toast ── */
>>>>>>> 29f2233 (fifth update)
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
    /* Apply saved theme icon */
    const saved = localStorage.getItem('tp-theme') || 'dark';
    const icon  = document.getElementById('theme-icon');
    if(icon) icon.textContent = saved === 'dark' ? '☀️' : '🌙';

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

<<<<<<< HEAD
    // Move session toasts into the fixed container and animate them
    document.querySelectorAll('[data-toast]').forEach(toast => {
        toast.remove(); // remove from page flow
=======
    document.querySelectorAll('[data-toast]').forEach(toast => {
        toast.remove();
>>>>>>> 29f2233 (fifth update)
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
</script>

<script>
<<<<<<< HEAD
    // Clear flash messages on back/forward navigation
=======
>>>>>>> 29f2233 (fifth update)
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            document.querySelectorAll('[data-toast]').forEach(el => el.remove());
        }
    });
</script>

@yield('extra-scripts')
</body>
</html>