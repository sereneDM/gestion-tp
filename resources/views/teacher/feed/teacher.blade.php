@extends('layouts.app')

@section('title', 'Accueil')
@section('page-title', 'Fil d\'actualité')
@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.dashboard') }}
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

body {
    font-family: var(--font-body);
    background: var(--surface-2);
    color: var(--ink);
}

/* ── Page layout ── */
.feed-wrapper {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 1.5rem;
    max-width: 1100px;
    margin: 0 auto;
    padding: 0.5rem 0 2rem;
    align-items: start;
}

/* ── Sidebar ── */
.sidebar {
    position: sticky;
    top: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.sidebar-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    box-shadow: var(--shadow-sm);
}

.sidebar-title {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ink-4);
    margin-bottom: 1rem;
}

.stat-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.6rem;
}

.stat-tile {
    background: var(--surface-2);
    border-radius: var(--radius-md);
    padding: 0.85rem 0.75rem;
    text-align: center;
}

.stat-tile-val {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.03em;
    line-height: 1;
    font-family: var(--font-serif);
}

.stat-tile-lbl {
    font-size: 0.7rem;
    color: var(--ink-3);
    margin-top: 0.3rem;
}

.type-list { display: flex; flex-direction: column; gap: 0.4rem; }

.type-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0.65rem;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    cursor: pointer;
    transition: background 0.15s;
    color: var(--ink-2);
    border: 1px solid transparent;
}
.type-item:hover { background: var(--surface-2); }
.type-item.active {
    background: var(--accent-bg);
    color: var(--accent);
    border-color: rgba(61,90,254,0.15);
    font-weight: 600;
}
.type-item-left { display: flex; align-items: center; gap: 0.5rem; }
.type-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    flex-shrink: 0;
}
.type-count {
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.1rem 0.45rem;
    border-radius: 100px;
    background: var(--surface-3);
    color: var(--ink-3);
}

/* ── Main feed area ── */
.feed-main { display: flex; flex-direction: column; gap: 0; }

/* ── Top bar ── */
.feed-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}

.feed-heading {
    font-family: var(--font-serif);
    font-size: 1.65rem;
    color: var(--ink);
    letter-spacing: -0.01em;
}

.btn-new {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: var(--accent);
    color: white;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.85rem;
    font-weight: 600;
    font-family: var(--font-body);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
}
.btn-new::after {
    content: "";
    position: absolute;
    top: 0; left: -60%;
    width: 40%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transform: skewX(-20deg);
    animation: shimmer 3s infinite;
}
@keyframes shimmer {
    0%, 60% { left: -60%; }
    80%, 100% { left: 120%; }
}
.btn-new:hover {
    background: var(--accent-2);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(61,90,254,0.35);
}
.btn-new:active { transform: scale(0.98); }

.btn-new i { font-size: 16px; }

/* ── Post cards ── */
.post-list { display: flex; flex-direction: column; gap: 0.85rem; }

.post-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-lg);
    padding: 1.4rem 1.5rem;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}

.post-card::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    border-radius: 3px 0 0 3px;
    background: var(--line-2);
    transition: background 0.2s;
}

.post-card:hover {
    border-color: var(--line-2);
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}
.post-card:hover::before { background: var(--accent); }

.post-card.type-announcement::before { background: var(--danger); }
.post-card.type-reminder::before     { background: var(--warning); }
.post-card.type-general::before      { background: var(--accent); }
.post-card.type-tp_posted::before    { background: var(--success); }

/* card inner layout */
.post-top {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.post-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid var(--line);
}

.post-meta-block { flex: 1; min-width: 0; }

.post-badge-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.35rem;
    flex-wrap: wrap;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 0.18rem 0.6rem;
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

.post-title {
    font-size: 0.97rem;
    font-weight: 700;
    color: var(--ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    letter-spacing: -0.01em;
}

.post-time {
    font-size: 0.75rem;
    color: var(--ink-4);
    margin-top: 0.15rem;
}

.post-body {
    margin-top: 0.9rem;
    font-size: 0.875rem;
    color: var(--ink-2);
    line-height: 1.65;
    white-space: pre-line;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.post-footer {
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.post-meta-pills { display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }

.pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0.25rem 0.7rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--ink-3);
    background: var(--surface-2);
    border: 1px solid var(--line);
}
.pill i { font-size: 13px; }

.pill-link {
    text-decoration: none;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}
.pill-link:hover {
    background: var(--accent-bg);
    color: var(--accent);
    border-color: rgba(61,90,254,0.2);
}

/* post actions */
.post-actions { display: flex; align-items: center; gap: 0.25rem; }

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0.3rem 0.65rem;
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--ink-3);
    background: none;
    border: none;
    cursor: pointer;
    font-family: var(--font-body);
    transition: background 0.15s, color 0.15s;
    text-decoration: none;
}
.action-btn i { font-size: 15px; }
.action-btn:hover { background: var(--surface-2); color: var(--ink); }

.action-btn.like-btn.liked { color: var(--danger); }
.action-btn.like-btn:hover { color: var(--danger); background: var(--danger-bg); }

.like-icon { display: inline-block; transition: transform 0.15s; }
.like-btn:hover .like-icon,
.like-btn.liked .like-icon { transform: scale(1.25); }
.like-btn:active { transform: scale(0.95); }

/* due date chip */
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

/* attachment btn */
.attachment-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.4rem 0.9rem;
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

/* divider between cards */
.post-divider {
    height: 1px;
    background: var(--line);
    display: none;
}

/* empty state */
.no-posts {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--surface);
    border: 1px dashed var(--line-2);
    border-radius: var(--radius-xl);
    color: var(--ink-3);
}
.no-posts-icon {
    width: 64px; height: 64px;
    border-radius: 18px;
    background: var(--surface-2);
    border: 1px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
    font-size: 28px;
    color: var(--ink-4);
}
.no-posts h3 { color: var(--ink-2); font-size: 1rem; font-weight: 600; margin-bottom: 0.4rem; }
.no-posts p  { font-size: 0.875rem; max-width: 280px; margin: 0 auto; }

/* ── Pagination ── */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 1.5rem;
    gap: 0.3rem;
}
.page-link {
    color: var(--ink-2);
    background: var(--surface);
    border: 1px solid var(--line);
    padding: 0.45rem 0.8rem;
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.page-link:hover { background: var(--surface-2); border-color: var(--line-2); }
.page-item.active .page-link { background: var(--accent); color: white; border-color: var(--accent); }
.page-item.disabled .page-link { color: var(--ink-4); cursor: not-allowed; }

/* ── Modal ── */
#post-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(13,17,23,0.45);
    backdrop-filter: blur(6px);
    z-index: 300;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-box {
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

.modal-header {
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

.modal-title-group { display: flex; align-items: center; gap: 0.75rem; }

.modal-icon {
    width: 38px; height: 38px;
    border-radius: var(--radius-md);
    background: var(--accent-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    font-size: 18px;
}

.modal-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--ink);
}
.modal-subtitle {
    font-size: 0.75rem;
    color: var(--ink-4);
    margin-top: 1px;
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

.modal-body { padding: 1.5rem 1.75rem; }

/* ── Form elements ── */
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
}
.form-input::placeholder { color: var(--ink-4); }
.form-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}

textarea.form-input {
    min-height: 110px;
    resize: vertical;
    line-height: 1.6;
}

select.form-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7585' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 2.5rem;
    cursor: pointer;
}

.char-counter {
    text-align: right;
    font-size: 0.7rem;
    margin-top: 0.3rem;
    color: var(--ink-4);
    transition: color 0.2s;
}

.error {
    font-size: 0.75rem;
    color: var(--danger);
    margin-top: 0.35rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* type selector cards */
.type-selector {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
}

.type-card {
    position: relative;
}

.type-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0; height: 0;
}

.type-card-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 0.75rem 0.5rem;
    border: 1.5px solid var(--line);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--ink-3);
    text-align: center;
}

.type-card-label i { font-size: 20px; }

.type-card-label:hover {
    border-color: var(--line-2);
    background: var(--surface-2);
}

.type-card input[type="radio"]:checked + .type-card-label {
    border-color: var(--accent);
    background: var(--accent-bg);
    color: var(--accent);
}

/* courses dropdown */
.dropdown-trigger {
    width: 100%;
    padding: 0.75rem 1rem;
    background: var(--surface);
    border: 1px solid var(--line-2);
    border-radius: var(--radius-md);
    color: var(--ink);
    text-align: left;
    cursor: pointer;
    font-size: 0.875rem;
    font-family: var(--font-body);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.dropdown-trigger:hover,
.dropdown-trigger:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(61,90,254,0.1);
}

.dropdown-panel {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0; right: 0;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    z-index: 400;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.dropdown-search {
    padding: 0.6rem;
    border-bottom: 1px solid var(--line);
}

.dropdown-search input {
    width: 100%;
    padding: 0.55rem 0.85rem;
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    color: var(--ink);
    font-size: 0.85rem;
    font-family: var(--font-body);
}
.dropdown-search input::placeholder { color: var(--ink-4); }
.dropdown-search input:focus { outline: none; border-color: var(--accent); }

.dropdown-list {
    max-height: 200px;
    overflow-y: auto;
    padding: 0.4rem;
}

.dropdown-opt {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.5rem 0.65rem;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-size: 0.875rem;
    color: var(--ink-2);
    transition: background 0.1s;
}
.dropdown-opt:hover { background: var(--surface-2); }
.dropdown-opt input[type="checkbox"] { accent-color: var(--accent); flex-shrink: 0; }
.dropdown-opt.all-opt {
    border-bottom: 1px solid var(--line);
    margin-bottom: 0.35rem;
    padding-bottom: 0.65rem;
    color: var(--purple);
    font-weight: 600;
}

/* modal footer */
.modal-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.75rem;
    border-top: 1px solid var(--line);
    background: var(--surface-2);
    border-radius: 0 0 var(--radius-xl) var(--radius-xl);
}

.btn-cancel {
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
.btn-cancel:hover { background: var(--surface-3); }

.btn-submit {
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
    position: relative;
    overflow: hidden;
}
.btn-submit::after {
    content: "";
    position: absolute;
    top: 0; left: -60%;
    width: 40%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
    transform: skewX(-20deg);
    animation: shimmer 3s infinite;
}
.btn-submit:hover { background: var(--accent-2); transform: translateY(-1px); }
.btn-submit i { font-size: 15px; }

/* breadcrumb */
.breadcrumb { background: transparent; padding: 0; margin-bottom: 1rem; }
.breadcrumb-item { color: var(--ink-4); font-size: 0.82rem; }
.breadcrumb-item a { color: var(--ink-3); text-decoration: none; }
.breadcrumb-item a:hover { color: var(--ink); }
.breadcrumb-item.active { color: var(--ink-2); font-weight: 600; }
.breadcrumb-item + .breadcrumb-item::before { color: var(--line-2); content: "/"; }

/* scrollbar */
.modal-box::-webkit-scrollbar,
.dropdown-list::-webkit-scrollbar { width: 5px; }
.modal-box::-webkit-scrollbar-track,
.dropdown-list::-webkit-scrollbar-track { background: transparent; }
.modal-box::-webkit-scrollbar-thumb,
.dropdown-list::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 10px; }

@media (max-width: 900px) {
    .feed-wrapper { grid-template-columns: 1fr; }
    .sidebar { position: static; }
}
</style>
@endsection

@section('content')

<div class="feed-wrapper">

    {{-- ── MAIN FEED ── --}}
    <div class="feed-main">

        <div class="feed-topbar">
            <h1 class="feed-heading">Fil d'actualité</h1>
            <button type="button" id="open-post-modal" class="btn-new">
                <i class="ti ti-pencil"></i> Nouvelle publication
            </button>
        </div>

        <div class="post-list">
            @forelse($posts as $post)
                <article class="post-card type-{{ $post->type }}"
                         onclick="if(event.target.closest('form,a,button')) return; window.location='{{ route('posts.show', $post->id) }}'">

                    <div class="post-top">
                        <img src="{{ $post->user->profile_picture_url }}"
                             alt="{{ $post->user->name }}"
                             class="post-avatar">

                        <div class="post-meta-block">
                            <div class="post-badge-row">
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
                            <div class="post-title">{{ $post->title }}</div>
                            <div class="post-time">
                                {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <p class="post-body">{{ $post->content }}</p>

                    <div class="post-footer">
                        <div class="post-meta-pills">
                            @if($post->class)
                                <span class="pill">
                                    <i class="ti ti-school"></i>
                                    {{ $post->class->name }}
                                    <span style="color:var(--ink-4)">· {{ $post->class->students->count() }}</span>
                                </span>
                            @else
                                <span class="pill">
                                    <i class="ti ti-world"></i> Tous les étudiants
                                </span>
                            @endif

                            @if($post->tp && $post->tp->due_date)
                                <span class="due-chip">
                                    <i class="ti ti-calendar-due"></i>
                                    {{ $post->tp->due_date->format('d/m/Y') }}
                                </span>
                            @endif

                            @if($post->tp)
                                <a href="{{ route('teacher.tps.show', $post->tp->id) }}" class="attachment-btn">
                                    <i class="ti ti-eye"></i> Voir le TP
                                </a>
                            @endif

                            @if($post->attachment)
                                <a href="{{ asset('storage/' . $post->attachment) }}" target="_blank" class="attachment-btn">
                                    <i class="ti ti-paperclip"></i> Pièce jointe
                                </a>
                            @endif
                        </div>

                        <div class="post-actions">
                            <button class="action-btn like-btn {{ $post->is_liked ? 'liked' : '' }}"
                                    data-id="{{ $post->id }}">
                                <span class="like-icon">
                                    <i class="ti ti-heart{{ $post->is_liked ? '-filled' : '' }}"></i>
                                </span>
                                <span class="like-count">{{ $post->likes_count }}</span>
                            </button>

                            <a href="{{ route('posts.show', $post->id) }}#comments"
                               class="action-btn">
                                <i class="ti ti-message-circle"></i>
                                {{ $post->comments->reduce(fn($c, $r) => $c + 1 + $r->replies->count(), 0) }}
                            </a>
                        </div>
                    </div>

                </article>
            @empty
                <div class="no-posts">
                    <div class="no-posts-icon">
                        <i class="ti ti-news"></i>
                    </div>
                    <h3>Aucune publication</h3>
                    <p>Créez votre première publication pour communiquer avec vos étudiants.</p>
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div style="margin-top:1.5rem;">{{ $posts->links() }}</div>
        @endif

    </div>

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">

        <div class="sidebar-card">
            <div class="sidebar-title">Vue d'ensemble</div>
            <div class="stat-grid">
                <div class="stat-tile">
                    <div class="stat-tile-val">{{ $posts->total() }}</div>
                    <div class="stat-tile-lbl">Publications</div>
                </div>
                <div class="stat-tile">
                    <div class="stat-tile-val">{{ $courses->count() }}</div>
                    <div class="stat-tile-lbl">Cours</div>
                </div>
            </div>
        </div>

        <div class="sidebar-card">
            <div class="sidebar-title">Filtrer par type</div>
            <div class="type-list">
                <div class="type-item active" data-filter="all">
                    <div class="type-item-left">
                        <div class="type-dot" style="background:var(--ink-4)"></div>
                        Tous
                    </div>
                    <span class="type-count">{{ $posts->total() }}</span>
                </div>
                <div class="type-item" data-filter="announcement">
                    <div class="type-item-left">
                        <div class="type-dot" style="background:var(--danger)"></div>
                        Annonces
                    </div>
                </div>
                <div class="type-item" data-filter="reminder">
                    <div class="type-item-left">
                        <div class="type-dot" style="background:var(--warning)"></div>
                        Rappels
                    </div>
                </div>
                <div class="type-item" data-filter="general">
                    <div class="type-item-left">
                        <div class="type-dot" style="background:var(--accent)"></div>
                        Général
                    </div>
                </div>
                <div class="type-item" data-filter="tp_posted">
                    <div class="type-item-left">
                        <div class="type-dot" style="background:var(--success)"></div>
                        TPs
                    </div>
                </div>
            </div>
        </div>

    </aside>

</div>

{{-- ── MODAL ── --}}
<div id="post-modal-backdrop">
    <div class="modal-box">

        <div class="modal-header">
            <div class="modal-title-group">
                <div class="modal-icon">
                    <i class="ti ti-pencil-plus"></i>
                </div>
                <div>
                    <div class="modal-title">Créer une publication</div>
                    <div class="modal-subtitle">Visible par vos étudiants</div>
                </div>
            </div>
            <button type="button" id="close-post-modal" class="modal-close">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-body">

                {{-- Type selector --}}
                <div class="form-group">
                    <label class="form-label">Type de publication</label>
                    <div class="type-selector">
                        <div class="type-card">
                            <input type="radio" name="type" id="type-announcement" value="announcement"
                                   {{ old('type', 'general') === 'announcement' ? 'checked' : '' }}>
                            <label class="type-card-label" for="type-announcement">
                                <i class="ti ti-speakerphone" style="color:var(--danger)"></i>
                                Annonce
                            </label>
                        </div>
                        <div class="type-card">
                            <input type="radio" name="type" id="type-reminder" value="reminder"
                                   {{ old('type') === 'reminder' ? 'checked' : '' }}>
                            <label class="type-card-label" for="type-reminder">
                                <i class="ti ti-clock" style="color:var(--warning)"></i>
                                Rappel
                            </label>
                        </div>
                        <div class="type-card">
                            <input type="radio" name="type" id="type-general" value="general"
                                   {{ old('type', 'general') === 'general' ? 'checked' : '' }}>
                            <label class="type-card-label" for="type-general">
                                <i class="ti ti-pin" style="color:var(--accent)"></i>
                                Général
                            </label>
                        </div>
                    </div>
                    @error('type')<div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>@enderror
                </div>

                {{-- Title --}}
                <div class="form-group">
                    <label class="form-label" for="title">Titre</label>
                    <input type="text" class="form-input" id="title" name="title"
                           value="{{ old('title') }}"
                           placeholder="Ex: Rappel — TP à rendre vendredi"
                           maxlength="100" required>
                    <div class="char-counter" id="title-counter">0 / 100</div>
                    @error('title')<div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>@enderror
                </div>

                {{-- Courses --}}
                <div class="form-group">
                    <label class="form-label">Cours ciblés</label>
                    <div style="position:relative;">
                        <button type="button" id="courses-trigger" class="dropdown-trigger">
                            <span id="courses-trigger-label">
                                <i class="ti ti-world" style="font-size:15px;vertical-align:-2px;margin-right:4px"></i>
                                Tous les étudiants
                            </span>
                            <i class="ti ti-chevron-down" id="courses-chevron" style="font-size:16px;transition:transform 0.2s;"></i>
                        </button>

                        <div id="courses-panel" class="dropdown-panel">
                            <div class="dropdown-search">
                                <input type="text" id="courses-search" placeholder="Rechercher un cours...">
                            </div>
                            <div class="dropdown-list">
                                <label class="dropdown-opt all-opt" id="opt-all">
                                    <input type="checkbox" id="course-all">
                                    <i class="ti ti-world" style="font-size:15px;color:var(--purple)"></i>
                                    <span>Tous mes étudiants</span>
                                </label>
                                @foreach($courses as $course)
                                    <label class="dropdown-opt course-opt" data-name="{{ strtolower($course->name) }}">
                                        <input type="checkbox" name="class_ids[]" value="{{ $course->id }}"
                                               class="course-cb"
                                               {{ is_array(old('class_ids')) && in_array($course->id, old('class_ids')) ? 'checked' : '' }}>
                                        <i class="ti ti-book" style="font-size:14px;color:var(--ink-3)"></i>
                                        <span>
                                            {{ $course->name }}
                                            <span style="color:var(--ink-4);font-size:0.78rem;">({{ $course->students->count() }})</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('class_ids')<div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>@enderror
                </div>

                {{-- Content --}}
                <div class="form-group">
                    <label class="form-label" for="content">Contenu</label>
                    <textarea class="form-input" id="content" name="content"
                              maxlength="2000" required
                              placeholder="Écrivez votre message...">{{ old('content') }}</textarea>
                    <div class="char-counter" id="content-counter">0 / 2000</div>
                    @error('content')<div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>@enderror
                </div>

                {{-- Attachment --}}
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Pièce jointe <span style="color:var(--ink-4);font-weight:400">(optionnel)</span></label>
                    <x-file-upload id="attachment" name="attachment"
                                   accept=".pdf,.jpg,.jpeg,.png,.zip"
                                   hint="PDF, JPG, PNG, ZIP · max 10 Mo" />
                    @error('attachment')<div class="error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>@enderror
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="close-post-modal-2">Annuler</button>
                <button type="submit" class="btn-submit">
                    <i class="ti ti-send"></i> Publier
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('extra-scripts')
<script>
// ── Modal ──
const modal     = document.getElementById('post-modal-backdrop');
const openBtn   = document.getElementById('open-post-modal');
const closeBtn  = document.getElementById('close-post-modal');
const closeBtn2 = document.getElementById('close-post-modal-2');

function openModal()  { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
function closeModal() { modal.style.display = 'none';  document.body.style.overflow = ''; }

openBtn.addEventListener('click', openModal);
closeBtn.addEventListener('click', closeModal);
closeBtn2.addEventListener('click', closeModal);
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

@if($errors->any())
    openModal();
@endif

// ── Courses dropdown ──
(function () {
    const trigger   = document.getElementById('courses-trigger');
    const panel     = document.getElementById('courses-panel');
    const chevron   = document.getElementById('courses-chevron');
    const labelEl   = document.getElementById('courses-trigger-label');
    const allCb     = document.getElementById('course-all');
    const search    = document.getElementById('courses-search');
    const courseCbs = () => document.querySelectorAll('.course-cb');

    trigger.addEventListener('click', e => {
        e.stopPropagation();
        const open = panel.style.display === 'block';
        panel.style.display = open ? 'none' : 'block';
        chevron.style.transform = open ? '' : 'rotate(180deg)';
        if (!open) setTimeout(() => search.focus(), 50);
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('#courses-trigger') && !e.target.closest('#courses-panel')) {
            panel.style.display = 'none';
            chevron.style.transform = '';
        }
    });

    search.addEventListener('input', () => {
        const q = search.value.toLowerCase();
        document.querySelectorAll('.course-opt').forEach(opt => {
            opt.style.display = opt.dataset.name.includes(q) ? '' : 'none';
        });
    });

    function updateLabel() {
        const checked = [...courseCbs()].filter(cb => cb.checked);
        if (checked.length === 0 || allCb.checked) {
            labelEl.innerHTML = '<i class="ti ti-world" style="font-size:15px;vertical-align:-2px;margin-right:4px"></i> Tous les étudiants';
        } else if (checked.length === 1) {
            const name = checked[0].closest('label').querySelector('span').childNodes[0].textContent.trim();
            labelEl.innerHTML = '<i class="ti ti-book" style="font-size:15px;vertical-align:-2px;margin-right:4px"></i> ' + name;
        } else {
            labelEl.innerHTML = '<i class="ti ti-books" style="font-size:15px;vertical-align:-2px;margin-right:4px"></i> ' + checked.length + ' cours sélectionnés';
        }
    }

    allCb.addEventListener('change', () => {
        if (allCb.checked) courseCbs().forEach(cb => cb.checked = false);
        updateLabel();
    });
    document.addEventListener('change', e => {
        if (e.target.classList.contains('course-cb')) {
            if (e.target.checked) allCb.checked = false;
            updateLabel();
        }
    });

    updateLabel();
})();

// ── Char counters ──
function makeCounter(inputId, counterId, max) {
    const input   = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    function update() {
        const len = input.value.length;
        counter.textContent = len + ' / ' + max;
        counter.style.color = len >= max ? '#e53935' : len >= max * 0.85 ? '#f59e0b' : '';
    }
    input.addEventListener('input', update);
    update();
}
makeCounter('title',   'title-counter',   100);
makeCounter('content', 'content-counter', 2000);

// ── Sidebar filter ──
document.querySelectorAll('.type-item').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.type-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        const filter = item.dataset.filter;
        document.querySelectorAll('.post-card').forEach(card => {
            card.style.display = (filter === 'all' || card.classList.contains('type-' + filter)) ? '' : 'none';
        });
    });
});

// ── Likes ──
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
</script>
@endsection