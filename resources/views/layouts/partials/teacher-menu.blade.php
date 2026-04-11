
<a href="{{ route('feed.index') }}" class="menu-item {{ request()->routeIs('feed.*') || request()->routeIs('posts.*') ? 'active' : '' }}">
    <span class="menu-item-icon">📰</span>
    <span class="menu-item-text">Accueil</span>
</a>
<a href="{{ route('notifications.index') }}" class="menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
    <span class="menu-item-icon">🔔</span>
    <span class="menu-item-text">Notifications</span>
    @php
        $unreadCount = App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
    @endphp
    @if($unreadCount > 0)
    <span id="notif-badge" style="background: #dc3545; color: white; padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.75rem; margin-left: auto;">
        {{ $unreadCount }}
    </span>
@else
    <span id="notif-badge" style="display:none; background: #dc3545; color: white; padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.75rem; margin-left: auto;"></span>
@endif
</a>

<div class="menu-section">Mes Cours</div>

<a href="{{ route('teacher.courses.index') }}" class="menu-item {{ request()->routeIs('teacher.courses.*') || request()->routeIs('teacher.tps.*') ? 'active' : '' }}">
    <span class="menu-item-icon">📚</span>
    <span class="menu-item-text">Gérer mes cours</span>
</a>

<div class="menu-section">Étudiants</div>

<a href="{{ route('teacher.progress.index') }}" class="menu-item {{ request()->routeIs('teacher.progress.*') ? 'active' : '' }}">
    <span class="menu-item-icon">📊</span>
    <span class="menu-item-text">Suivi des étudiants</span>
</a>

<a href="{{ route('teacher.attendance.index') }}" class="menu-item {{ request()->routeIs('teacher.attendance.*') ? 'active' : '' }}">
    <span class="menu-item-icon">✓</span>
    <span class="menu-item-text">Présences</span>
</a>

<div class="menu-section">Statistiques</div>

<a href="{{ route('teacher.statistics') }}" class="menu-item {{ request()->routeIs('teacher.statistics') ? 'active' : '' }}">
    <span class="menu-item-icon">📈</span>
    <span class="menu-item-text">Mes statistiques</span>
</a>

<div class="menu-section">Paramètres</div>

<a href="{{ route('profile.edit') }}" class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <span class="menu-item-icon">⚙️</span>
    <span class="menu-item-text">Mon profil</span>
</a>