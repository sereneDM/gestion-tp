<a href="{{ route('student.dashboard') }}" class="menu-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
    <span class="menu-item-icon">🏠</span>
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

<a href="{{ route('student.join-course.form') }}" class="menu-item {{ request()->routeIs('student.join-course.form') ? 'active' : '' }}">
    <span class="menu-item-icon">➕</span>
    <span class="menu-item-text">Rejoindre un cours</span>
</a>

<a href="{{ route('student.my-courses') }}" class="menu-item {{ request()->routeIs('student.my-courses') || request()->routeIs('student.courses.*') || request()->routeIs('student.tps.*') ? 'active' : '' }}">
    <span class="menu-item-icon">📚</span>
    <span class="menu-item-text">Mes cours</span>
</a>

<div class="menu-section">Mes Travaux</div>

<a href="{{ route('student.submissions.index') }}" class="menu-item {{ request()->routeIs('student.submissions.*') ? 'active' : '' }}">
    <span class="menu-item-icon">📄</span>
    <span class="menu-item-text">Mes soumissions</span>
</a>

<div class="menu-section">Progression</div>

<a href="{{ route('student.progress') }}" class="menu-item {{ request()->routeIs('student.progress') ? 'active' : '' }}">
    <span class="menu-item-icon">📊</span>
    <span class="menu-item-text">Ma progression</span>
</a>

<div class="menu-section">Paramètres</div>

<a href="{{ route('profile.edit') }}" class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <span class="menu-item-icon">⚙️</span>
    <span class="menu-item-text">Mon profil</span>
</a>