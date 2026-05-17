@extends('layouts.app')

@section('title', $post->title)
@section('page-title', 'Publication')

@section('breadcrumbs')
    {{ Breadcrumbs::render('posts.show', $post) }}
@endsection

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
    --shadow-md:  0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --shadow-lg:  0 12px 40px rgba(0,0,0,0.1), 0 2px 8px rgba(0,0,0,0.05);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

/* ── Page wrapper ── */
.show-wrapper {
    max-width: 860px;
    margin: 0 auto;
    padding: 0.5rem 0 3rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* ── Cards ── */
.card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

/* ── Post card accent bar ── */
.post-accent {
    height: 4px;
    background: var(--line-2);
}
.post-accent.type-announcement { background: var(--danger); }
.post-accent.type-reminder     { background: var(--warning); }
.post-accent.type-general      { background: var(--accent); }
.post-accent.type-tp_posted    { background: var(--success); }

.post-body-wrap { padding: 1.75rem 2rem; }

/* ── Badge ── */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.2rem 0.65rem;
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.02em;
}
.badge i { font-size: 12px; }
.badge-announcement { background: var(--danger-bg);  color: var(--danger);  }
.badge-reminder     { background: var(--warning-bg); color: var(--warning); }
.badge-general      { background: var(--accent-bg);  color: var(--accent);  }
.badge-tp_posted    { background: var(--success-bg); color: var(--success); }

/* ── Post header ── */
.post-header-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.post-author-row {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}

.post-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--line);
    flex-shrink: 0;
}

.post-meta-block {}
.post-author-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--ink);
}
.post-meta-time {
    font-size: 0.75rem;
    color: var(--ink-4);
    margin-top: 2px;
}

/* ── 3-dots menu ── */
.menu-wrap { position: relative; }

.post-menu-btn {
    background: var(--surface-2);
    border: 1px solid var(--line);
    color: var(--ink-3);
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}
.post-menu-btn:hover {
    background: var(--surface-3);
    border-color: var(--line-2);
    color: var(--ink);
}

.post-menu-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    min-width: 160px;
    z-index: 100;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}
.post-menu-dropdown button {
    width: 100%;
    text-align: left;
    padding: 0.7rem 1rem;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.85rem;
    font-family: var(--font-body);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.15s;
    color: var(--ink-2);
}
.post-menu-dropdown button i { font-size: 15px; }
.menu-edit-btn:hover   { background: var(--accent-bg); color: var(--accent); }
.menu-delete-btn       { color: var(--danger) !important; }
.menu-delete-btn:hover { background: var(--danger-bg); }

/* ── Post title & content ── */
.post-title {
    font-family: var(--font-serif);
    font-size: 1.6rem;
    color: var(--ink);
    letter-spacing: -0.02em;
    margin: 0.25rem 0 0.75rem;
    line-height: 1.25;
}

.post-content {
    font-size: 0.95rem;
    color: var(--ink-2);
    line-height: 1.75;
    white-space: pre-line;
}

/* ── Meta pills ── */
.post-pills-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 1.25rem;
    padding-top: 1.25rem;
    border-top: 1px solid var(--line);
}

.pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0.3rem 0.75rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--ink-3);
    background: var(--surface-2);
    border: 1px solid var(--line);
}
.pill i { font-size: 13px; }

.due-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.3rem 0.75rem;
    background: var(--warning-bg);
    border: 1px solid rgba(245,158,11,0.25);
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--warning);
}
.due-chip i { font-size: 13px; }

.attachment-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.35rem 0.9rem;
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    color: var(--ink-2);
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
}
.attachment-btn i { font-size: 14px; }
.attachment-btn:hover { background: var(--surface-3); border-color: var(--line-2); }

/* ── Like row ── */
.post-actions-row {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--line);
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0.3rem 0.65rem;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--ink-3);
    background: none;
    border: none;
    cursor: pointer;
    font-family: var(--font-body);
    transition: background 0.15s, color 0.15s;
}
.action-btn i { font-size: 15px; }
.action-btn:hover { background: var(--surface-2); color: var(--ink); }
.action-btn.like-btn.liked { color: var(--danger); }
.action-btn.like-btn:hover { color: var(--danger); background: var(--danger-bg); }
.like-icon { display: inline-block; transition: transform 0.15s; }
.like-btn:hover .like-icon,
.like-btn.liked .like-icon { transform: scale(1.25); }
.like-btn:active { transform: scale(0.95); }

/* ── Comments section ── */
.comments-card { padding: 1.75rem 2rem; }

.comments-heading {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.comments-heading i { font-size: 18px; color: var(--ink-3); }
.comment-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: 100px;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--ink-3);
    padding: 0.1rem 0.5rem;
    margin-left: 0.25rem;
}

/* ── Comment item ── */
.comment {
    display: flex;
    gap: 0.85rem;
    margin-bottom: 1.25rem;
}

.comment-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    object-fit: cover;
    border: 1.5px solid var(--line);
    flex-shrink: 0;
    margin-top: 2px;
}

.comment-body { flex: 1; min-width: 0; }

.comment-bubble {
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: 0 var(--radius-lg) var(--radius-lg) var(--radius-lg);
    padding: 0.8rem 1rem;
    transition: background 0.3s, border-color 0.3s;
}

.comment-header-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.35rem;
    flex-wrap: wrap;
}

.comment-author {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--ink);
}

.comment-role {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.1rem 0.45rem;
    border-radius: 100px;
    background: var(--surface-3);
    color: var(--ink-4);
    letter-spacing: 0.03em;
}
.comment-role.teacher {
    background: var(--accent-bg);
    color: var(--accent);
}

.comment-time {
    font-size: 0.72rem;
    color: var(--ink-4);
    margin-left: auto;
}

.comment-content {
    font-size: 0.875rem;
    color: var(--ink-2);
    line-height: 1.6;
    word-break: break-word;
}

.comment-footer {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.4rem;
    padding-left: 0.25rem;
}

.comment-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.2rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--ink-4);
    background: none;
    border: none;
    cursor: pointer;
    font-family: var(--font-body);
    transition: background 0.15s, color 0.15s;
}
.comment-action-btn i { font-size: 13px; }
.comment-action-btn:hover { background: var(--surface-2); color: var(--ink-2); }
.comment-action-btn.delete-btn:hover { color: var(--danger); background: var(--danger-bg); }
.comment-action-btn.liked       { color: var(--danger); }
.comment-action-btn.like-comment-btn:hover { color: var(--danger); background: var(--danger-bg); }

/* ── Replies ── */
.replies {
    margin-top: 0.75rem;
    margin-left: 1.25rem;
    padding-left: 1rem;
    border-left: 2px solid var(--line);
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.reply { display: flex; gap: 0.75rem; }

.reply-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 1.5px solid var(--line);
    flex-shrink: 0;
    margin-top: 2px;
}

/* ── Reply form ── */
.reply-form { display: none; margin-top: 0.75rem; }
.reply-form.active { display: block; }

/* ── Comment input ── */
.comment-input-wrap { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--line); }

.comment-input {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    background: var(--surface);
    color: var(--ink);
    resize: vertical;
    font-family: var(--font-body);
    font-size: 0.875rem;
    line-height: 1.6;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.comment-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}
.comment-input::placeholder { color: var(--ink-4); }

.comment-form-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.65rem;
}

.btn-submit-comment {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1.2rem;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.85rem;
    font-weight: 700;
    font-family: var(--font-body);
    cursor: pointer;
    transition: background 0.2s, transform 0.15s;
    box-shadow: 0 2px 8px rgba(61,90,254,0.25);
}
.btn-submit-comment i { font-size: 14px; }
.btn-submit-comment:hover { background: var(--accent-2); transform: translateY(-1px); }

.btn-cancel-reply {
    padding: 0.55rem 1rem;
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    color: var(--ink-2);
    font-size: 0.85rem;
    font-family: var(--font-body);
    cursor: pointer;
    transition: background 0.15s;
}
.btn-cancel-reply:hover { background: var(--surface-3); }

.no-comments {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--ink-4);
    font-size: 0.875rem;
}
.no-comments i { font-size: 32px; display: block; margin-bottom: 0.5rem; color: var(--line-2); }

/* ── Edit modal ── */
.edit-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(13,17,23,0.5);
    backdrop-filter: blur(6px);
    z-index: 999;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.edit-modal-overlay.active { display: flex; }

.edit-modal {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    width: 100%;
    max-width: 620px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-lg);
    animation: fadeUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) both;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

.edit-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.4rem 1.75rem 1.2rem;
    border-bottom: 1px solid var(--line);
    position: sticky;
    top: 0;
    background: var(--surface);
    z-index: 1;
}

.edit-modal-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.edit-modal-icon {
    width: 38px; height: 38px;
    border-radius: var(--radius-md);
    background: var(--accent-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-size: 18px;
}

.edit-modal-title-text {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ink);
}

.modal-close {
    width: 32px; height: 32px;
    border-radius: var(--radius-sm);
    background: var(--surface-2);
    border: 1px solid var(--line);
    color: var(--ink-3);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: background 0.15s, color 0.15s;
}
.modal-close:hover { background: var(--surface-3); color: var(--ink); }

.edit-modal-body { padding: 1.5rem 1.75rem; }

.form-group { margin-bottom: 1.25rem; }

.form-label {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--ink-3);
    margin-bottom: 0.45rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-family: var(--font-body);
    background: var(--surface);
    color: var(--ink);
    transition: border-color 0.2s, box-shadow 0.2s;
    appearance: none;
    box-sizing: border-box;
}
.form-input::placeholder { color: var(--ink-4); }
.form-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}
textarea.form-input { min-height: 120px; resize: vertical; line-height: 1.6; }
select.form-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7585' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 2.5rem;
    cursor: pointer;
}

.current-attachment-row {
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.current-attachment-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--accent);
    font-size: 0.85rem;
    word-break: break-all;
}
.current-attachment-name i { font-size: 15px; flex-shrink: 0; }

.remove-attachment-label {
    display: inline-flex !important;
    align-items: center;
    gap: 0.4rem;
    cursor: pointer;
    color: var(--danger) !important;
    font-size: 0.8rem !important;
    font-weight: 500 !important;
    margin: 0 !important;
    white-space: nowrap;
    flex-shrink: 0;
}
.remove-attachment-label input[type="checkbox"] { width: auto !important; accent-color: var(--danger); }

.edit-modal-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.75rem;
    border-top: 1px solid var(--line);
    background: var(--surface-2);
    border-radius: 0 0 var(--radius-xl) var(--radius-xl);
}

.btn-cancel-edit {
    padding: 0.6rem 1.1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--line);
    background: var(--surface);
    color: var(--ink-2);
    font-size: 0.875rem;
    font-weight: 500;
    font-family: var(--font-body);
    cursor: pointer;
    transition: background 0.15s;
}
.btn-cancel-edit:hover { background: var(--surface-3); }

.btn-save-edit {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.65rem 1.4rem;
    border-radius: var(--radius-md);
    border: none;
    background: var(--accent);
    color: white;
    font-size: 0.875rem;
    font-weight: 700;
    font-family: var(--font-body);
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
    transition: background 0.2s, transform 0.15s;
}
.btn-save-edit i { font-size: 15px; }
.btn-save-edit:hover { background: var(--accent-2); transform: translateY(-1px); }

/* ── Comment highlight animation ──
   Starts at accent-bg (blue tint) and fades back to surface-2 (the
   bubble's normal grey background) so the end state is always correct. ── */
@keyframes highlightFade {
    0%   { background: var(--accent-bg); border-color: rgba(61,90,254,0.3); }
    100% { background: var(--surface-2); border-color: var(--line); }
}
.comment-highlight {
    animation: highlightFade 2.5s ease-out forwards;
}

/* scrollbar */
.edit-modal::-webkit-scrollbar { width: 5px; }
.edit-modal::-webkit-scrollbar-track { background: transparent; }
.edit-modal::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 10px; }

@media (max-width: 640px) {
    .post-body-wrap, .comments-card { padding: 1.25rem; }
    .post-title { font-size: 1.3rem; }
}
</style>
@endsection

@section('content')

<div class="show-wrapper">

    {{-- ── POST CARD ── --}}
    <div class="card">
        <div class="post-accent type-{{ $post->type }}"></div>

        <div class="post-body-wrap">

            <div class="post-header-row">
                <div class="post-author-row">
                    <img src="{{ $post->user->profile_picture_url }}"
                         alt="{{ $post->user->name }}"
                         class="post-avatar">
                    <div class="post-meta-block">
                        <div class="post-badge-row" style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.3rem;">
                            <span class="badge badge-{{ $post->type }}">
                                @if($post->type === 'announcement')
                                    <i class="ti ti-speakerphone"></i> Annonce
                                @elseif($post->type === 'tp_posted')
                                    <i class="ti ti-file-text"></i> TP
                                @elseif($post->type === 'reminder')
                                    <i class="ti ti-clock"></i> Rappel
                                @else
                                    <i class="ti ti-pin"></i> Général
                                @endif
                            </span>
                        </div>
                        <div class="post-author-name">{{ $post->user->name }}</div>
                        <div class="post-meta-time">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                @if(Auth::user()->isTeacher() && $post->user_id === Auth::id())
                    <div class="menu-wrap">
                        <button class="post-menu-btn" onclick="togglePostMenu()" aria-label="Options">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="post-menu-dropdown" id="post-menu">
                            <button type="button" class="menu-edit-btn" onclick="openEditModal()">
                                <i class="ti ti-edit"></i> Modifier
                            </button>
                            <form method="POST" action="{{ route('posts.destroy', $post->id) }}" style="display:block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="menu-delete-btn"
                                        onclick="return confirm('Supprimer cette publication?')">
                                    <i class="ti ti-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <h1 class="post-title">{{ $post->title }}</h1>
            <div class="post-content">{{ $post->content }}</div>

            {{-- Meta pills ── --}}
            <div class="post-pills-row">
                @if($post->class)
                    <span class="pill">
                        <i class="ti ti-school"></i> {{ $post->class->name }}
                    </span>
                @else
                    <span class="pill">
                        <i class="ti ti-world"></i> Tous les étudiants
                    </span>
                @endif

                @if($post->tp && $post->tp->due_date)
                    <span class="due-chip">
                        <i class="ti ti-calendar-due"></i>
                        Échéance : {{ $post->tp->due_date->format('d/m/Y à H:i') }}
                    </span>
                @endif

                @if($post->tp)
                    @if(Auth::user()->isTeacher())
                        <a href="{{ route('teacher.tps.show', $post->tp->id) }}" class="attachment-btn">
                            <i class="ti ti-eye"></i> Voir le TP
                        </a>
                    @else
                        <a href="{{ route('student.tps.show', $post->tp->id) }}" class="attachment-btn">
                            <i class="ti ti-eye"></i> Voir le TP
                        </a>
                    @endif
                @endif

                @if($post->attachment)
                    <a href="{{ asset('storage/' . $post->attachment) }}" target="_blank" class="attachment-btn">
                        <i class="ti ti-paperclip"></i> Pièce jointe
                    </a>
                @endif
            </div>

            {{-- Like ── --}}
            <div class="post-actions-row">
                <button class="action-btn like-btn {{ $post->is_liked ? 'liked' : '' }}"
                        data-id="{{ $post->id }}">
                    <span class="like-icon">
                        <i class="ti ti-heart{{ $post->is_liked ? '-filled' : '' }}"></i>
                    </span>
                    <span class="like-count">{{ $post->likes_count }}</span>
                </button>
            </div>

        </div>
    </div>

    {{-- ── COMMENTS CARD ── --}}
    <div class="card">
        <div class="comments-card" id="comments">

            <div class="comments-heading">
                <i class="ti ti-message-circle"></i>
                Commentaires
                <span class="comment-count-badge">
                    {{ $post->comments->reduce(fn($carry, $c) => $carry + 1 + $c->replies->count(), 0) }}
                </span>
            </div>

            @forelse($post->comments as $comment)
                <div class="comment" id="comment-{{ $comment->id }}">
                    <img src="{{ $comment->user->profile_picture_url }}"
                         alt="{{ $comment->user->name }}"
                         class="comment-avatar">

                    <div class="comment-body">
                        <div class="comment-bubble">
                            <div class="comment-header-row">
                                <span class="comment-author">{{ $comment->user->name }}</span>
                                <span class="comment-role {{ $comment->user->isTeacher() ? 'teacher' : '' }}">
                                    {{ $comment->user->isTeacher() ? 'Enseignant' : 'Étudiant' }}
                                </span>
                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="comment-content">{{ $comment->content }}</div>
                        </div>

                        <div class="comment-footer">
                            <button class="comment-action-btn"
                                    onclick="toggleReply('reply-form-{{ $comment->id }}', '{{ addslashes($comment->user->name) }}')">
                                <i class="ti ti-arrow-back-up"></i> Répondre
                            </button>
                            <button class="comment-action-btn like-comment-btn {{ $comment->is_liked ? 'liked' : '' }}"
                                    data-id="{{ $comment->id }}">
                                <span class="like-icon">
                                    <i class="ti ti-heart{{ $comment->is_liked ? '-filled' : '' }}"></i>
                                </span>
                                <span class="like-count">{{ $comment->likes_count }}</span>
                            </button>
                            @if($comment->user_id === Auth::id())
                                <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="comment-action-btn delete-btn"
                                            onclick="return confirm('Supprimer ce commentaire?')">
                                        <i class="ti ti-trash"></i> Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Replies ── --}}
                        @if($comment->replies->count() > 0)
                            <div class="replies">
                                @foreach($comment->replies as $reply)
                                    <div class="reply" id="comment-{{ $reply->id }}">
                                        <img src="{{ $reply->user->profile_picture_url }}"
                                             alt="{{ $reply->user->name }}"
                                             class="reply-avatar">
                                        <div class="comment-body">
                                            <div class="comment-bubble">
                                                <div class="comment-header-row">
                                                    <span class="comment-author">{{ $reply->user->name }}</span>
                                                    <span class="comment-role {{ $reply->user->isTeacher() ? 'teacher' : '' }}">
                                                        {{ $reply->user->isTeacher() ? 'Enseignant' : 'Étudiant' }}
                                                    </span>
                                                    <span class="comment-time">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="comment-content">{{ $reply->content }}</div>
                                            </div>
                                            <div class="comment-footer">
                                                <button class="comment-action-btn"
                                                        onclick="toggleReply('reply-form-{{ $comment->id }}', '{{ addslashes($reply->user->name) }}'); document.getElementById('reply-form-{{ $comment->id }}').scrollIntoView({behavior:'smooth', block:'center'});">
                                                    <i class="ti ti-arrow-back-up"></i> Répondre
                                                </button>
                                                <button class="comment-action-btn like-comment-btn {{ $reply->is_liked ? 'liked' : '' }}"
                                                        data-id="{{ $reply->id }}">
                                                    <span class="like-icon">
                                                        <i class="ti ti-heart{{ $reply->is_liked ? '-filled' : '' }}"></i>
                                                    </span>
                                                    <span class="like-count">{{ $reply->likes_count }}</span>
                                                </button>
                                                @if($reply->user_id === Auth::id())
                                                    <form method="POST" action="{{ route('comments.destroy', $reply->id) }}" style="display:inline;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="comment-action-btn delete-btn"
                                                                onclick="return confirm('Supprimer ce commentaire?')">
                                                            <i class="ti ti-trash"></i> Supprimer
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Reply form ── --}}
                        <div class="reply-form" id="reply-form-{{ $comment->id }}">
                            <form method="POST" action="{{ route('posts.comments.store', $post->id) }}" style="margin-top:0.75rem;">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <textarea name="content" class="comment-input" rows="3" required
                                          placeholder="Ctrl+Entrée pour envoyer..."></textarea>
                                <div class="comment-form-actions">
                                    <button type="submit" class="btn-submit-comment">
                                        <i class="ti ti-arrow-back-up"></i> Répondre
                                    </button>
                                    <button type="button" class="btn-cancel-reply"
                                            onclick="cancelReply('reply-form-{{ $comment->id }}')">Annuler</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <div class="no-comments">
                    <i class="ti ti-message-off"></i>
                    Aucun commentaire — soyez le premier à réagir.
                </div>
            @endforelse

            {{-- New comment ── --}}
            <div class="comment-input-wrap">
                <form method="POST" action="{{ route('posts.comments.store', $post->id) }}">
                    @csrf
                    <textarea name="content" class="comment-input" rows="3" required
                              placeholder="Écrivez un commentaire… (Ctrl+Entrée pour envoyer)"></textarea>
                    <div class="comment-form-actions">
                        <button type="submit" class="btn-submit-comment">
                            <i class="ti ti-send"></i> Commenter
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

{{-- ── EDIT MODAL ── --}}
@if(Auth::user()->isTeacher() && $post->user_id === Auth::id())
<div class="edit-modal-overlay" id="edit-modal">
    <div class="edit-modal">

        <div class="edit-modal-header">
            <div class="edit-modal-title">
                <div class="edit-modal-icon">
                    <i class="ti ti-edit"></i>
                </div>
                <div class="edit-modal-title-text">Modifier la publication</div>
            </div>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="edit-modal-body">

                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-input">
                        <option value="announcement" {{ $post->type === 'announcement' ? 'selected' : '' }}>Annonce</option>
                        <option value="reminder"     {{ $post->type === 'reminder'     ? 'selected' : '' }}>Rappel</option>
                        <option value="general"      {{ $post->type === 'general'      ? 'selected' : '' }}>Général</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Titre *</label>
                    <input type="text" name="title" class="form-input"
                           value="{{ $post->title }}" maxlength="100" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Contenu *</label>
                    <textarea name="content" class="form-input" rows="6" required>{{ $post->content }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Pièce jointe</label>

                    @if($post->attachment)
                        <div class="current-attachment-row">
                            <span class="current-attachment-name">
                                <i class="ti ti-paperclip"></i>
                                {{ basename($post->attachment) }}
                            </span>
                            <label class="remove-attachment-label">
                                <input type="checkbox" name="remove_attachment" value="1">
                                <i class="ti ti-trash" style="font-size:13px;"></i> Supprimer
                            </label>
                        </div>
                    @endif

                    <x-file-upload id="edit-attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.zip"
                        hint="{{ $post->attachment ? 'Un nouveau fichier remplacera l\'existant' : 'PDF, JPG, PNG, ZIP · max 10 Mo' }}" />
                </div>

            </div>

            <div class="edit-modal-footer">
                <button type="button" class="btn-cancel-edit" onclick="closeEditModal()">Annuler</button>
                <button type="submit" class="btn-save-edit">
                    <i class="ti ti-device-floppy"></i> Enregistrer
                </button>
            </div>
        </form>

    </div>
</div>
@endif

@endsection

@section('extra-scripts')
<script>
function toggleReply(id, authorName) {
    const form     = document.getElementById(id);
    const textarea = form.querySelector('textarea');
    const isOpening = !form.classList.contains('active');
    form.classList.toggle('active');
    if (isOpening) {
        if (authorName && textarea.value.trim() === '') {
            textarea.value = '@' + authorName + ' ';
        }
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
    }
}

function cancelReply(id) {
    const form     = document.getElementById(id);
    const textarea = form.querySelector('textarea');
    textarea.value = '';
    form.classList.remove('active');
}

function togglePostMenu() {
    const menu = document.getElementById('post-menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.post-menu-btn') && !e.target.closest('#post-menu')) {
        const menu = document.getElementById('post-menu');
        if (menu) menu.style.display = 'none';
    }
});

function openEditModal() {
    document.getElementById('edit-modal').classList.add('active');
    const menu = document.getElementById('post-menu');
    if (menu) menu.style.display = 'none';
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.remove('active');
    document.body.style.overflow = '';
}

document.getElementById('edit-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditModal();
});

document.querySelectorAll('.comment-input').forEach(textarea => {
    textarea.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            this.closest('form').submit();
        }
    });
});

// ── Post like ──
document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id  = btn.dataset.id;
        const res = await fetch(`/posts/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        const data = await res.json();
        const icon = btn.querySelector('.like-icon i');
        icon.className = data.liked ? 'ti ti-heart-filled' : 'ti ti-heart';
        btn.querySelector('.like-count').textContent = data.count;
        btn.classList.toggle('liked', data.liked);
    });
});

// ── Comment like ──
document.querySelectorAll('.like-comment-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id  = btn.dataset.id;
        const res = await fetch(`/comments/${id}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        const data = await res.json();
        const icon = btn.querySelector('.like-icon i');
        icon.className = data.liked ? 'ti ti-heart-filled' : 'ti ti-heart';
        btn.querySelector('.like-count').textContent = data.count;
        btn.classList.toggle('liked', data.liked);
    });
});

// ── Scroll to & highlight target comment ──
// Applies .comment-highlight directly to .comment-bubble so the animation
// fades from accent-bg back to surface-2 (the bubble's actual background),
// making the highlight visible and the end state correct.
(function () {
    const newCommentId = @json(session('new_comment_id'));
    const scrollTo     = @json(session('scroll_to'));
    const hash         = window.location.hash;

    const specificHash    = hash && /^#comment-\d+$/.test(hash) ? hash.replace('#', '') : null;
    const sessionTarget   = newCommentId ? 'comment-' + newCommentId : scrollTo;
    const scrollTargetId  = specificHash || sessionTarget || (hash === '#comments' ? 'comments' : null);
    const highlightTargetId = specificHash || sessionTarget;

    if (scrollTargetId) {
        const el = document.getElementById(scrollTargetId);
        if (el) {
            setTimeout(() => {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (highlightTargetId && highlightTargetId === scrollTargetId) {
                    // Target the bubble directly so animation ends at surface-2
                    const bubble = el.querySelector('.comment-bubble');
                    if (bubble) bubble.classList.add('comment-highlight');
                }
            }, 100);
        }
    }
})();
</script>
@endsection