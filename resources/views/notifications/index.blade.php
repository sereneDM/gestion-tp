@extends('layouts.app')
@section('title', 'Notifications')
@section('page-title', 'Mes Notifications')
@section('extra-styles')
<style>
    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .unread-count {
        font-size: 1rem;
        color: var(--tp-text-muted);
    }
    .unread-count span {
        color: #a5b4fc;
        font-weight: bold;
    }
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.875rem;
        display: inline-block;
        font-weight: 500;
    }
    .btn-primary {
        background: #4f46e5;
        color: white;
    }
    .btn-primary:hover { background: #4338ca; }
    .btn-secondary {
        background: var(--tp-bg-raised);
        color: var(--tp-text-secondary);
        border: 1px solid var(--tp-border);
        margin-left: 0.5rem;
    }
    .btn-secondary:hover { background: var(--tp-hover-bg); }
    .notification-card {
        padding: 1.25rem 1.5rem;
        border-radius: 0.75rem;
        border-left: 4px solid #4f46e5;
        transition: all 0.2s;
        cursor: pointer;
        margin-bottom: 0.75rem;
    }
    .notification-card:hover {
        transform: translateX(4px);
    }
    .notification-card.unread {
        background: var(--tp-bg-raised);
        border: 1px solid var(--tp-border);
        border-left: 4px solid #6366f1;
    }
    .notification-card.read {
        background: var(--tp-bg-surface);
        border: 1px solid var(--tp-border);
        border-left: 4px solid var(--tp-border);
        opacity: 0.7;
    }
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    .notification-title {
        font-weight: bold;
        color: var(--tp-text-primary);
        margin-bottom: 0.4rem;
        font-size: 1rem;
    }
    .notification-message {
        color: var(--tp-text-muted);
        margin-bottom: 0.25rem;
        line-height: 1.5;
        font-size: 0.9rem;
    }
    .notification-time {
        font-size: 0.8rem;
        color: var(--tp-text-faint);
        white-space: nowrap;
        margin-left: 1rem;
    }
    .type-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    .type-new_tp            { background: rgba(34,197,94,0.15);   color: #86efac; }
    .type-submission_graded { background: rgba(99,102,241,0.15);  color: #a5b4fc; }
    .type-new_submission    { background: rgba(251,191,36,0.15);  color: #fde68a; }
    .type-new_post          { background: rgba(239,68,68,0.15);   color: #fca5a5; }
    .type-student_joined    { background: rgba(16,185,129,0.15);  color: #6ee7b7; }
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--tp-text-faint);
    }
    .empty-state .icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    .empty-state h2 {
        color: var(--tp-text-muted);
        margin-bottom: 0.5rem;
    }
    .empty-state p {
        color: var(--tp-text-faint);
        font-size: 0.9rem;
    }
</style>
@endsection
@section('content')
    <div class="notifications-header">
        <div class="unread-count">
            <span>{{ $unreadCount }}</span> notification(s) non lue(s)
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
        <form method="POST"
              action="{{ route('notifications.mark-read', $notification->id) }}"
              id="form-{{ $notification->id }}">
            @csrf
            <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}"
                 id="notif-{{ $notification->id }}"
                 onclick="markRead({{ $notification->id }})">
                <div class="notification-header">
                    <div>
                        <span class="type-badge type-{{ $notification->type }}">
                            @if($notification->type === 'new_tp') 📝 Nouveau TP
                            @elseif($notification->type === 'submission_graded') ⭐ Note
                            @elseif($notification->type === 'new_submission') 📤 Soumission
                            @elseif($notification->type === 'new_post') 📢 Annonce
                            @elseif($notification->type === 'student_joined') 👤 Nouvel étudiant
                            @else 🔔 Notification
                            @endif
                        </span>
                        <div class="notification-title">{{ $notification->title }}</div>
                        <div class="notification-message">{{ $notification->message }}</div>
                    </div>
                    <div class="notification-time">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </form>
    @empty
        <div class="empty-state">
            <div class="icon">🔔</div>
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
        const countEl = document.querySelector('.unread-count span');
        if (countEl) {
            const current = parseInt(countEl.textContent);
            if (!isNaN(current) && current > 0) {
                countEl.textContent = current - 1;
            }
        }
        const badge = document.querySelector('a[href="{{ route('notifications.index') }}"] span');
        if (badge) {
            const badgeCount = parseInt(badge.textContent) - 1;
            if (badgeCount <= 0) badge.style.display = 'none';
            else badge.textContent = badgeCount;
        }
    }
    document.getElementById('form-' + id).submit();
}
</script>
@endsection