<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Plateforme TP')</title>

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Arial,sans-serif;background:#f4f4f4;overflow-x:hidden}

/* Topbar */
.topbar{
    position:fixed;top:0;left:0;width:260px;height:52px;
    background:#2c3e50;display:flex;align-items:center;
    padding:0 1rem;z-index:101;gap:1rem;transition:.25s
}
.topbar.collapsed{width:60px}
.topbar-title{color:#fff;font-size:1rem;font-weight:700}
.topbar.collapsed .topbar-title{display:none}

.toggle-btn{
    background:none;border:none;color:#fff;cursor:pointer;
    padding:6px 8px;border-radius:6px;font-size:1.2rem
}
.toggle-btn:hover{background:rgba(255,255,255,.15)}

/* Sidebar */
.sidebar{
    width:260px;
    background:#2c3e50;
    color:#fff;
    position:fixed;
    top:52px;
    left:0;
    height:calc(100vh - 52px);
    display:flex;
    flex-direction:column;
    transition:.25s;
}
.sidebar.collapsed{width:60px}

/* Header */
.sidebar-header{
    padding:1.2rem;
    background:#1a252f;
    border-bottom:1px solid #34495e;
    display:flex;
    align-items:center;
    gap:12px;
    min-height: 72px;
}
.sidebar-header img{
    width:40px;
    height:40px;
    border-radius:50%;
    flex-shrink: 0;
}
.sidebar-header div {
    overflow: hidden;
}
.sidebar-header div h2 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #fff;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    line-height: 1.3;
}
.sidebar-header div p {
    font-size: 0.8rem;
    color: #95a5a6;
    margin-top: 2px;
}

/* Collapse header */
.sidebar.collapsed .sidebar-header{
    justify-content:center;
}
.sidebar.collapsed .sidebar-header div{
    display:none;
}

/* Menu */
.sidebar-menu{
    flex: 1;
    overflow: hidden;
    padding-bottom: 60px;
}

/* Sections */
.menu-section{
    padding:0.8rem 1.5rem 0.4rem;
    font-size:0.7rem;
    color:#95a5a6;
    text-transform:uppercase;
    font-weight:700;
}
.sidebar.collapsed .menu-section{display:none}

/* Items */
.menu-item{
    display:flex;
    align-items:center;
    padding:0.9rem 1.5rem;
    color:#fff;
    text-decoration:none;
    border-left:3px solid transparent;
    transition:.2s;
}
.menu-item:hover,
.menu-item.active{
    background:#34495e;
    border-left-color:#3498db;
}

/* Icons */
.menu-item-icon{
    font-size:1.1rem;
    width:24px;
    text-align:center;
    margin-right:1rem;
    flex-shrink: 0;
}

/* Text */
.menu-item-text{
    font-size:0.95rem;
}

/* Collapsed */
.sidebar.collapsed .menu-item{
    justify-content:center;
    padding:1rem 0;
}
.sidebar.collapsed .menu-item-icon{
    margin:0;
}
.sidebar.collapsed .menu-item-text{
    display:none;
}

/* Logout */
.logout-section{
    height: 60px;
}

/* Main */
.main-content{
    margin-left:260px;
    padding:2rem;
    min-height:100vh;
    transition:.25s;
}
.main-content.collapsed{margin-left:60px}

.content-wrapper{
    background:#fff;
    padding:2rem;
    border-radius:8px;
    box-shadow:0 2px 4px rgba(0,0,0,.1);
}

.page-header{
    margin-bottom:2rem;
    padding-bottom:1rem;
    border-bottom:2px solid #f0f0f0;
}
.page-header h1{
    font-size:1.6rem;
}

/* Alerts */
.alert{padding:1rem;border-radius:4px;margin-bottom:1rem}
.alert-success{background:#d4edda;color:#155724}
.alert-error{background:#f8d7da;color:#721c24}

</style>

@yield('extra-styles')
</head>

<body>

<!-- Topbar -->
<div class="topbar" id="topbar">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <span class="topbar-title">Plateforme TP</span>
</div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">

    <div class="sidebar-header">
        <img src="{{ Auth::user()->profile_picture_url }}" alt="Avatar">
        <div>
            <h2>{{ Auth::user()->name }}</h2>
            <p>
                @if(Auth::user()->isAdmin())Administrateur
                @elseif(Auth::user()->isTeacher())Enseignant
                @else Étudiant @endif
            </p>
        </div>
    </div>

    <nav class="sidebar-menu">
        @if(Auth::user()->isAdmin())
            @include('layouts.partials.admin-menu')
        @elseif(Auth::user()->isTeacher())
            @include('layouts.partials.teacher-menu')
        @else
            @include('layouts.partials.student-menu')
        @endif
    </nav>

    <div class="logout-section">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-item" style="width:100%;background:none;border:none;cursor:pointer">
                <span class="menu-item-icon">🚪</span>
                <span class="menu-item-text">Déconnexion</span>
            </button>
        </form>
    </div>

</aside>

<!-- Main -->
<main class="main-content" id="mainContent">

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">✗ {{ session('error') }}</div>
    @endif

   <div class="content-wrapper">
    <div class="page-header">
        @if(Breadcrumbs::exists())
            {{ Breadcrumbs::render() }}
        @else
            <h1>@yield('page-title')</h1>
        @endif
    </div>
    @yield('content')
</div>

</main>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('topbar').classList.toggle('collapsed');
    document.getElementById('mainContent').classList.toggle('collapsed');
}
</script>

@yield('extra-scripts')
</body>
</html>