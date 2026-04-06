<a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <span class="menu-item-icon">🏠</span>
    <span class="menu-item-text">Tableau de bord</span>
</a>

<div class="menu-section">Gestion</div>

<a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
    <span class="menu-item-icon">👥</span>
    <span class="menu-item-text">Utilisateurs</span>
</a>

<a href="{{ route('admin.classes.index') }}" class="menu-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
    <span class="menu-item-icon">📚</span>
    <span class="menu-item-text">Classes</span>
</a>

<a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
    <span class="menu-item-icon">⚙️</span>
    <span class="menu-item-text">Paramètres</span>
</a>

<div class="menu-section">Supervision</div>

<a href="{{ route('admin.statistics') }}" class="menu-item {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
    <span class="menu-item-icon">📊</span>
    <span class="menu-item-text">Statistiques</span>
</a>

<a href="{{ route('admin.system-logs') }}" class="menu-item {{ request()->routeIs('admin.system-logs') ? 'active' : '' }}">
    <span class="menu-item-icon">📋</span>
    <span class="menu-item-text">Logs système</span>
</a>

<div class="menu-section">Mon Compte</div>

<a href="{{ route('profile.edit') }}" class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <span class="menu-item-icon">👤</span>
    <span class="menu-item-text">Mon profil</span>
</a>