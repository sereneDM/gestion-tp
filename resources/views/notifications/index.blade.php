@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Mes Notifications')

@section('extra-styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

<style>
:root {
    --ink:        #0d1117;
    --ink-2:      #3d4550;
    --ink-3:      #6b7585;
    --ink-4:      #9aa3af;

    --line:       #e8ebef;
    --line-2:     #d1d6dd;

    --surface:    #ffffff;
    --surface-2:  #f5f6f8;
    --surface-3:  #eef0f3;

    --accent:     #4f46e5;
    --accent-2:   #6366f1;
    --accent-bg:  #eef2ff;

    --danger:     #ef4444;
    --danger-bg:  #fef2f2;

    --warning:    #f59e0b;
    --warning-bg: #fffbeb;

    --success:    #10b981;
    --success-bg: #ecfdf5;

    --purple:     #8b5cf6;
    --purple-bg:  #f5f0ff;

    --radius-sm:  8px;
    --radius-md:  12px;
    --radius-lg:  18px;
    --radius-xl:  24px;

    --shadow-sm:  0 1px 3px rgba(0,0,0,0.04),
                  0 1px 2px rgba(0,0,0,0.03);

    --shadow-md:  0 10px 30px rgba(0,0,0,0.07);

    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-body);
    background: var(--surface-2);
    color: var(--ink);
}

.page-wrapper {
    max-width: 780px;
    margin: 0 auto;
    padding: 0.5rem 0 3rem;
}

/* ───────────────────────────── */
/* Top Bar                       */
/* ───────────────────────────── */

.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 0.7rem;
}

.page-heading {
    font-family: var(--font-serif);
    font-size: 1.6rem;
    letter-spacing: -0.02em;
    color: var(--ink);
}

.unread-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-bg);
    color: var(--accent);
    border: 1px solid rgba(79,70,229,0.12);
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.18rem 0.6rem;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 0.55rem;
}

.btn-mark-all,
.btn-settings {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.62rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s, border-color 0.2s, transform 0.15s;
}

.btn-mark-all {
    background: var(--accent);
    color: white;
    border: none;
    cursor: pointer;
}

.btn-mark-all:hover {
    background: var(--accent-2);
    transform: translateY(-1px);
}

.btn-settings {
    background: var(--surface);
    color: var(--ink-2);
    border: 1px solid var(--line);
}

.btn-settings:hover {
    background: var(--surface-2);
}

.btn-mark-all i,
.btn-settings i {
    font-size: 15px;
}

/* ───────────────────────────── */
/* Notification List             */
/* ───────────────────────────── */

.notif-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.notif-card {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 1.15rem 1.25rem;
    box-shadow: var(--shadow-sm);
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    cursor: pointer;
}

.notif-card::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    border-radius: 3px 0 0 3px;
    background: var(--line-2);
    transition: background 0.2s;
}

.notif-card:hover {
    border-color: var(--line-2);
    box-shadow: var(--shadow-md);
    transform: translateX(3px);
}

.notif-card.unread {
    background: white;
}

.notif-card.read {
    opacity: 0.82;
}

/* ───────────────────────────── */
/* Accent Bars                   */
/* ───────────────────────────── */

.notif-card.type-new_tp::before          { background: var(--success); }
.notif-card.type-submission_graded::before { background: var(--accent); }
.notif-card.type-new_submission::before  { background: var(--warning); }
.notif-card.type-new_post::before        { background: var(--danger); }
.notif-card.type-student_joined::before  { background: var(--purple); }
.notif-card.type-new_comment::before     { background: var(--accent); }
.notif-card.type-post_liked::before,
.notif-card.type-comment_liked::before   { background: var(--purple); }

/* ───────────────────────────── */
/* Icon Wrap                     */
/* ───────────────────────────── */

.notif-icon-wrap {
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notif-icon-wrap i {
    font-size: 18px;
    stroke-width: 2;
}

.type-new_tp .notif-icon-wrap            { background: var(--success-bg); color: var(--success); }
.type-submission_graded .notif-icon-wrap { background: var(--accent-bg);  color: var(--accent); }
.type-new_submission .notif-icon-wrap    { background: var(--warning-bg); color: var(--warning); }
.type-new_post .notif-icon-wrap          { background: var(--danger-bg);  color: var(--danger); }
.type-student_joined .notif-icon-wrap    { background: var(--purple-bg);  color: var(--purple); }
.type-new_comment .notif-icon-wrap       { background: var(--accent-bg);  color: var(--accent); }
.type-post_liked .notif-icon-wrap,
.type-comment_liked .notif-icon-wrap     { background: var(--purple-bg);  color: var(--purple); }

/* ───────────────────────────── */
/* Notification Content          */
/* ───────────────────────────── */

.notif-body {
    flex: 1;
    min-width: 0;
}

.notif-header-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.8rem;
    margin-bottom: 0.35rem;
}

.notif-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    font-size: 0.68rem;
    font-weight: 700;
}

.type-new_tp .notif-badge            { background: var(--success-bg); color: var(--success); }
.type-submission_graded .notif-badge { background: var(--accent-bg);  color: var(--accent); }
.type-new_submission .notif-badge    { background: var(--warning-bg); color: var(--warning); }
.type-new_post .notif-badge          { background: var(--danger-bg);  color: var(--danger); }
.type-student_joined .notif-badge    { background: var(--purple-bg);  color: var(--purple); }
.type-new_comment .notif-badge       { background: var(--accent-bg);  color: var(--accent); }
.type-post_liked .notif-badge,
.type-comment_liked .notif-badge     { background: var(--purple-bg);  color: var(--purple); }

.notif-time {
    font-size: 0.72rem;
    color: var(--ink-4);
    white-space: nowrap;
}

.notif-title {
    font-size: 0.92rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--ink);
    margin-bottom: 0.2rem;
}

.notif-message {
    font-size: 0.83rem;
    line-height: 1.55;
    color: var(--ink-3);
}

/* ───────────────────────────── */
/* Empty State                   */
/* ───────────────────────────── */

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--surface);
    border: 1px dashed var(--line-2);
    border-radius: var(--radius-xl);
    color: var(--ink-3);
}

.empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 1.25rem;
    border-radius: 18px;
    background: var(--surface-2);
    border: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink-4);
}

.empty-icon i { font-size: 28px; }

.empty-state h3 {
    color: var(--ink-2);
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
}

.empty-state p { font-size: 0.88rem; }

/* ───────────────────────────── */
/* Pagination                    */
/* ───────────────────────────── */

.pagination-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.5rem;
    gap: 1rem;
}

.pagination-info {
    font-size: 0.8rem;
    color: var(--ink-4);
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-page {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid var(--line);
    background: var(--surface);
    color: var(--ink-2);
    transition: background 0.15s, border-color 0.15s, transform 0.15s;
}

.btn-page:hover {
    background: var(--surface-2);
    border-color: var(--line-2);
    transform: translateY(-1px);
}

.btn-page.disabled {
    opacity: 0.35;
    pointer-events: none;
}

.btn-page i { font-size: 15px; }

.page-dots {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.page-dot {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    color: var(--ink-3);
    border: 1px solid transparent;
    transition: background 0.15s, color 0.15s;
}

.page-dot:hover {
    background: var(--surface-3);
    color: var(--ink);
}

.page-dot.active {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
}
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <div class="topbar">
        <div class="topbar-left">
            <h1 class="page-heading">Notifications</h1>

            @if($unreadCount > 0)
                <span class="unread-badge">{{ $unreadCount }} non lue(s)</span>
            @endif
        </div>

        <div class="topbar-right">
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-mark-all">
                        <i class="ti ti-checks"></i>
                        Tout marquer comme lu
                    </button>
                </form>
            @endif

            <a href="{{ route('notification-settings') }}" class="btn-settings">
                <i class="ti ti-settings"></i>
                Paramètres
            </a>
        </div>
    </div>

    <div class="notif-list">
        @forelse($notifications as $notification)
            <form method="POST"
                  action="{{ route('notifications.mark-read', $notification->id) }}"
                  id="form-{{ $notification->id }}">
                @csrf

                <div class="notif-card {{ $notification->is_read ? 'read' : 'unread' }} type-{{ $notification->type }}"
                     id="notif-{{ $notification->id }}"
                     onclick="markRead({{ $notification->id }})">

                    <div class="notif-icon-wrap">
                        <i class="ti
                            @if($notification->type === 'new_tp') ti-file-text
                            @elseif($notification->type === 'submission_graded') ti-star
                            @elseif($notification->type === 'new_submission') ti-upload
                            @elseif($notification->type === 'new_post') ti-speakerphone
                            @elseif($notification->type === 'student_joined') ti-user-plus
                            @elseif($notification->type === 'new_comment') ti-message-circle
                            @elseif($notification->type === 'post_liked') ti-heart
                            @elseif($notification->type === 'comment_liked') ti-heart
                            @else ti-bell
                            @endif
                        "></i>
                    </div>

                    <div class="notif-body">
                        <div class="notif-header-row">
                            <span class="notif-badge">
                                @if($notification->type === 'new_tp') Nouveau TP
                                @elseif($notification->type === 'submission_graded') Note reçue
                                @elseif($notification->type === 'new_submission') Soumission
                                @elseif($notification->type === 'new_post') Annonce
                                @elseif($notification->type === 'student_joined') Nouvel étudiant
                                @elseif($notification->type === 'new_comment') Commentaire
                                @elseif($notification->type === 'post_liked') J'aime · Publication
                                @elseif($notification->type === 'comment_liked') J'aime · Commentaire
                                @else Notification
                                @endif
                            </span>

                            <span class="notif-time">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="notif-title">
                            {{ preg_replace('/[\p{Emoji}\p{So}\p{Cn}]+/u', '', $notification->title) }}
                        </div>

                        <div class="notif-message">
                            {{ preg_replace('/[\p{Emoji}\p{So}\p{Cn}]+/u', '', $notification->message) }}
                        </div>
                    </div>

                </div>
            </form>

        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="ti ti-bell-off"></i>
                </div>
                <h3>Aucune notification</h3>
                <p>Vous n'avez pas encore de notifications.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="pagination-bar">

        <span class="pagination-info">
            Page {{ $notifications->currentPage() }} sur {{ $notifications->lastPage() }}
            &nbsp;·&nbsp;
            {{ $notifications->total() }} notification(s)
        </span>

        <div class="pagination-controls">

            @if($notifications->onFirstPage())
                <span class="btn-page disabled">
                    <i class="ti ti-arrow-left"></i> Précédent
                </span>
            @else
                <a href="{{ $notifications->previousPageUrl() }}" class="btn-page">
                    <i class="ti ti-arrow-left"></i> Précédent
                </a>
            @endif

            <div class="page-dots">
                @foreach($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                    @if(abs($page - $notifications->currentPage()) <= 2)
                        <a href="{{ $url }}"
                           class="page-dot {{ $page === $notifications->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @elseif(abs($page - $notifications->currentPage()) === 3)
                        <span class="page-dot" style="pointer-events:none; color: var(--ink-4);">…</span>
                    @endif
                @endforeach
            </div>

            @if($notifications->hasMorePages())
                <a href="{{ $notifications->nextPageUrl() }}" class="btn-page">
                    Suivant <i class="ti ti-arrow-right"></i>
                </a>
            @else
                <span class="btn-page disabled">
                    Suivant <i class="ti ti-arrow-right"></i>
                </span>
            @endif

        </div>
    </div>
    @endif

</div>
@endsection

@section('extra-scripts')
<script>
function markRead(id) {
    const card = document.getElementById('notif-' + id);

    if (card && card.classList.contains('unread')) {
        card.classList.remove('unread');
        card.classList.add('read');

        const countEl = document.querySelector('.unread-badge');

        if (countEl) {
            const current = parseInt(countEl.textContent);
            if (!isNaN(current) && current > 1) {
                countEl.textContent = (current - 1) + ' non lue(s)';
            } else {
                countEl.style.display = 'none';
            }
        }
    }

    document.getElementById('form-' + id).submit();
}
</script>
@endsection