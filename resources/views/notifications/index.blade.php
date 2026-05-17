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
    --accent:     #3d5afe;
    --accent-2:   #5271ff;
    --accent-bg:  #eef1ff;
    --danger:     #e53935;
    --danger-bg:  #fff0f0;
    --warning:    #f59e0b;
    --warning-bg: #fffbeb;
    --success:    #10b981;
    --success-bg: #ecfdf5;
    --purple:     #7c3aed;
    --purple-bg:  #f3f0ff;
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,0.07);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 780px; margin: 0 auto; padding: 0.5rem 0 3rem; }

/* ── Top bar ── */
.topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
.topbar-left { display: flex; align-items: center; gap: 0.6rem; }
.page-heading { font-family: var(--font-serif); font-size: 1.5rem; color: var(--ink); letter-spacing: -0.01em; }
.unread-badge {
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--accent-bg); color: var(--accent);
    border: 1px solid rgba(61,90,254,0.2);
    border-radius: 100px; font-size: 0.72rem; font-weight: 700; padding: 0.1rem 0.55rem;
}
.topbar-right { display: flex; align-items: center; gap: 0.5rem; }

.btn-mark-all {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1rem; border-radius: var(--radius-md); border: none;
    background: var(--accent); color: white; font-size: 0.8rem; font-weight: 600;
    font-family: var(--font-body); cursor: pointer; transition: background 0.15s;
}
.btn-mark-all:hover { background: var(--accent-2); }
.btn-mark-all i { font-size: 14px; }

.btn-settings {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1rem; border-radius: var(--radius-md);
    border: 1px solid var(--line); background: var(--surface); color: var(--ink-2);
    font-size: 0.8rem; font-weight: 500; text-decoration: none; transition: background 0.15s;
}
.btn-settings:hover { background: var(--surface-2); }
.btn-settings i { font-size: 14px; }

/* ── Notification list ── */
.notif-list { display: flex; flex-direction: column; gap: 0.6rem; }

.notif-card {
    background: var(--surface); border: 1px solid var(--line);
    border-radius: var(--radius-lg); padding: 1.1rem 1.25rem; cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    box-shadow: var(--shadow-sm); position: relative; overflow: hidden;
    display: flex; gap: 1rem; align-items: flex-start;
}
.notif-card::before {
    content: ""; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; border-radius: 3px 0 0 3px;
    background: var(--line-2); transition: background 0.2s;
}
.notif-card.unread::before { background: var(--accent); }
.notif-card.unread { background: var(--surface); }
.notif-card.read   { opacity: 0.65; }
.notif-card:hover  { border-color: var(--line-2); box-shadow: var(--shadow-md); transform: translateX(2px); }

/* ── Type accent bars ── */
.notif-card.type-new_tp::before            { background: var(--success); }
.notif-card.type-submission_graded::before { background: var(--accent);  }
.notif-card.type-new_submission::before    { background: var(--warning); }
.notif-card.type-new_post::before          { background: var(--danger);  }
.notif-card.type-student_joined::before    { background: var(--purple);  }
.notif-card.type-new_comment::before       { background: var(--accent);  }
.notif-card.type-post_liked::before        { background: var(--danger);  }
.notif-card.type-comment_liked::before     { background: var(--danger);  }

/* ── Icon wrap colors ── */
.notif-icon-wrap {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center; font-size: 16px;
}
.type-new_tp            .notif-icon-wrap { background: var(--success-bg); color: var(--success); }
.type-submission_graded .notif-icon-wrap { background: var(--accent-bg);  color: var(--accent);  }
.type-new_submission    .notif-icon-wrap { background: var(--warning-bg); color: var(--warning); }
.type-new_post          .notif-icon-wrap { background: var(--danger-bg);  color: var(--danger);  }
.type-student_joined    .notif-icon-wrap { background: var(--purple-bg);  color: var(--purple);  }
.type-new_comment       .notif-icon-wrap { background: var(--accent-bg);  color: var(--accent);  }
.type-post_liked        .notif-icon-wrap { background: var(--danger-bg);  color: var(--danger);  }
.type-comment_liked     .notif-icon-wrap { background: var(--danger-bg);  color: var(--danger);  }

.notif-body { flex: 1; min-width: 0; }
.notif-header-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.2rem; }

/* ── Badge colors ── */
.notif-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0.15rem 0.5rem; border-radius: 100px;
    font-size: 0.68rem; font-weight: 700; white-space: nowrap; flex-shrink: 0;
}
.type-new_tp            .notif-badge { background: var(--success-bg); color: var(--success); }
.type-submission_graded .notif-badge { background: var(--accent-bg);  color: var(--accent);  }
.type-new_submission    .notif-badge { background: var(--warning-bg); color: var(--warning); }
.type-new_post          .notif-badge { background: var(--danger-bg);  color: var(--danger);  }
.type-student_joined    .notif-badge { background: var(--purple-bg);  color: var(--purple);  }
.type-new_comment       .notif-badge { background: var(--accent-bg);  color: var(--accent);  }
.type-post_liked        .notif-badge { background: var(--danger-bg);  color: var(--danger);  }
.type-comment_liked     .notif-badge { background: var(--danger-bg);  color: var(--danger);  }

.notif-time    { font-size: 0.72rem; color: var(--ink-4); white-space: nowrap; }
.notif-title   { font-size: 0.875rem; font-weight: 700; color: var(--ink); margin-bottom: 0.15rem; }
.notif-message { font-size: 0.82rem; color: var(--ink-3); line-height: 1.5; }

/* ── Empty state ── */
.empty-state {
    text-align: center; padding: 4rem 2rem;
    background: var(--surface); border: 1px dashed var(--line-2);
    border-radius: var(--radius-xl); color: var(--ink-3);
}
.empty-icon {
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--surface-2); border: 1px solid var(--line);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem; font-size: 28px; color: var(--ink-4);
}
.empty-state h3 { color: var(--ink-2); font-size: 1rem; font-weight: 600; margin-bottom: 0.4rem; }
.empty-state p  { font-size: 0.875rem; }
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
                        <i class="ti ti-checks"></i> Tout marquer comme lu
                    </button>
                </form>
            @endif
            <a href="{{ route('notification-settings') }}" class="btn-settings">
                <i class="ti ti-settings"></i> Paramètres
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
                            @if($notification->type === 'new_tp')                ti-file-text
                            @elseif($notification->type === 'submission_graded') ti-star
                            @elseif($notification->type === 'new_submission')    ti-upload
                            @elseif($notification->type === 'new_post')          ti-speakerphone
                            @elseif($notification->type === 'student_joined')    ti-user-plus
                            @elseif($notification->type === 'new_comment')       ti-message-circle
                            @elseif($notification->type === 'post_liked')        ti-heart
                            @elseif($notification->type === 'comment_liked')     ti-heart
                            @else                                                ti-bell
                            @endif
                        "></i>
                    </div>

                    <div class="notif-body">
                        <div class="notif-header-row">
                            <span class="notif-badge">
                                @if($notification->type === 'new_tp')                Nouveau TP
                                @elseif($notification->type === 'submission_graded') Note reçue
                                @elseif($notification->type === 'new_submission')    Soumission
                                @elseif($notification->type === 'new_post')          Annonce
                                @elseif($notification->type === 'student_joined')    Nouvel étudiant
                                @elseif($notification->type === 'new_comment')       Commentaire
                                @elseif($notification->type === 'post_liked')        J'aime · Publication
                                @elseif($notification->type === 'comment_liked')     J'aime · Commentaire
                                @else                                                Notification
                                @endif
                            </span>
                            <span class="notif-time">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="notif-title">{{ $notification->title }}</div>
                        <div class="notif-message">{{ $notification->message }}</div>
                    </div>

                </div>
            </form>
        @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="ti ti-bell-off"></i></div>
                <h3>Aucune notification</h3>
                <p>Vous n'avez pas encore de notifications.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div style="margin-top:1.5rem;">{{ $notifications->links() }}</div>
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