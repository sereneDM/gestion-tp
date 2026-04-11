@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Mes Notifications')

@section('sidebar-menu')
    @if(Auth::user()->isStudent())
        @include('layouts.partials.student-menu')
    @elseif(Auth::user()->isTeacher())
        @include('layouts.partials.teacher-menu')
    @else
        @include('layouts.partials.admin-menu')
    @endif
@endsection

@section('extra-styles')
<style>
    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .btn-primary {
        background: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background: #0056b3;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .notification-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #007bff;
        transition: all 0.2s;
        cursor: pointer;
    }
    .notification-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .notification-card.unread {
        background: #e7f3ff;
        border-left-color: #007bff;
    }
    .notification-card.read {
        background: white;
        border-left-color: #ddd;
        opacity: 0.7;
    }
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.5rem;
    }
    .notification-icon {
        font-size: 1.5rem;
        margin-right: 1rem;
    }
    .notification-title {
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    .notification-message {
        color: #666;
        margin-bottom: 0.5rem;
        line-height: 1.5;
    }
    .notification-time {
        font-size: 0.85rem;
        color: #999;
    }
    .type-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
    }
    .type-new_tp {
        background: #d4edda;
        color: #155724;
    }
    .type-submission_graded {
        background: #d1ecf1;
        color: #0c5460;
    }
    .type-new_submission {
        background: #fff3cd;
        color: #856404;
    }
    .type-new_post {
        background: #f8d7da;
        color: #721c24;
    }
    .empty-state {
        background: white;
        padding: 3rem;
        text-align: center;
        border-radius: 8px;
        color: #999;
    }
</style>
@endsection

@section('content')
    <div class="notifications-header">
        <div>
            <span style="font-size: 1.2rem; color: #666;">
                {{ $unreadCount }} notification(s) non lue(s)
            </span>
        </div>
        <div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        ✓ Tout marquer comme lu
                    </button>
                </form>
            @endif
            <a href="{{ route('notification-settings') }}" class="btn btn-secondary">
                ⚙️ Paramètres
            </a>
        </div>
    </div>

    @forelse($notifications as $notification)
        <div style="margin: 0 0 1rem 0;">
            <form method="POST"
                  action="{{ route('notifications.mark-read', $notification->id) }}"
                  id="form-{{ $notification->id }}">
                @csrf
                <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}"
                     id="notif-{{ $notification->id }}"
                     onclick="markRead({{ $notification->id }})">
                    <div class="notification-header">
                        <div style="flex: 1;">
                            <span class="type-badge type-{{ $notification->type }}">
                                @if($notification->type === 'new_tp') 📝 Nouveau TP
                                @elseif($notification->type === 'submission_graded') ⭐ Note
                                @elseif($notification->type === 'new_submission') 📤 Soumission
                                @elseif($notification->type === 'new_post') 📢 Annonce
                                @else 🔔 Notification
                                @endif
                            </span>
                        </div>
                        <div class="notification-time">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="notification-title">{{ $notification->title }}</div>
                    <div class="notification-message">{{ $notification->message }}</div>
                </div>
            </form>
        </div>
    @empty
        <div class="empty-state">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🔔</div>
            <h2>Aucune notification</h2>
            <p>Vous n'avez pas encore de notifications</p>
        </div>
    @endforelse

    @if($notifications->hasPages())
        <div style="margin-top: 1.5rem;">
            {{ $notifications->links() }}
        </div>
    @endif

@endsection

@section('extra-scripts')
<script>
function markRead(id) {
    const card = document.getElementById('notif-' + id);
    if (card && card.classList.contains('unread')) {
        card.classList.remove('unread');
        card.classList.add('read');

        // Update page counter
        const countEl = document.querySelector('.notifications-header span');
        if (countEl) {
            const current = parseInt(countEl.textContent);
            if (!isNaN(current) && current > 0) {
                countEl.textContent = (current - 1) + ' notification(s) non lue(s)';
            }
        }

        // Update sidebar badge
        const badge = document.getElementById('notif-badge');
        if (badge) {
            const badgeCount = parseInt(badge.textContent) - 1;
            if (badgeCount <= 0) {
                badge.style.display = 'none';
            } else {
                badge.textContent = badgeCount;
            }
        }
    }

    document.getElementById('form-' + id).submit();
}
</script>
@endsection