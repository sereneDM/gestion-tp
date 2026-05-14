@extends('layouts.app')

@section('title', 'Accueil')
@section('page-title', 'Accueil')

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
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

/* ── Layout ── */
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

/* Quick actions */
.quick-actions-list {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.quick-action-link {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.55rem 0.7rem;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--ink-2);
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    border: 1px solid transparent;
}
.quick-action-link:hover {
    background: var(--accent-bg);
    color: var(--accent);
    border-color: rgba(61,90,254,0.15);
}
.quick-action-link i { font-size: 16px; flex-shrink: 0; }

/* ── Feed ── */
.feed-main { display: flex; flex-direction: column; }

.feed-heading {
    font-family: var(--font-serif);
    font-size: 1.65rem;
    color: var(--ink);
    letter-spacing: -0.01em;
    margin-bottom: 1.25rem;
}

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

.post-card:hover { border-color: var(--line-2); box-shadow: var(--shadow-md); transform: translateY(-1px); }
.post-card:hover::before { background: var(--accent); }
.post-card.type-announcement::before { background: var(--danger); }
.post-card.type-reminder::before     { background: var(--warning); }
.post-card.type-general::before      { background: var(--accent); }
.post-card.type-tp_posted::before    { background: var(--success); }

.post-top { display: flex; align-items: flex-start; gap: 1rem; }

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

.post-time { font-size: 0.75rem; color: var(--ink-4); margin-top: 0.15rem; }

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

        <h1 class="feed-heading">Fil d'actualité</h1>

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
                                </span>
                            @endif

                            @if($post->tp && $post->tp->due_date)
                                <span class="due-chip">
                                    <i class="ti ti-calendar-due"></i>
                                    {{ $post->tp->due_date->format('d/m/Y') }}
                                </span>
                            @endif

                            @if($post->tp)
                                <a href="{{ route('student.tps.show', $post->tp->id) }}" class="attachment-btn">
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

                            <a href="{{ route('posts.show', $post->id) }}#comments" class="action-btn">
                                <i class="ti ti-message-circle"></i>
                                {{ $post->comments->count() }}
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
                    <p>Les annonces de vos enseignants apparaîtront ici.</p>
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
                    <div class="stat-tile-val">{{ $enrolledCoursesCount }}</div>
                    <div class="stat-tile-lbl">Cours suivis</div>
                </div>
                <div class="stat-tile">
                    <div class="stat-tile-val">{{ $availableTPs }}</div>
                    <div class="stat-tile-lbl">TPs dispo</div>
                </div>
                <div class="stat-tile">
                    <div class="stat-tile-val">{{ $submittedCount }}</div>
                    <div class="stat-tile-lbl">Soumis</div>
                </div>
                <div class="stat-tile">
                    <div class="stat-tile-val">{{ $gradedCount }}</div>
                    <div class="stat-tile-lbl">Notés</div>
                </div>
            </div>
        </div>

        <div class="sidebar-card">
            <div class="sidebar-title">Actions rapides</div>
            <div class="quick-actions-list">
                <a href="{{ route('student.join-course.form') }}" class="quick-action-link">
                    <i class="ti ti-plus"></i> Rejoindre un cours
                </a>
                <a href="{{ route('student.my-courses') }}" class="quick-action-link">
                    <i class="ti ti-book"></i> Mes cours
                </a>
                <a href="{{ route('student.submissions.index') }}" class="quick-action-link">
                    <i class="ti ti-file-text"></i> Mes soumissions
                </a>
                <a href="{{ route('student.progress') }}" class="quick-action-link">
                    <i class="ti ti-chart-bar"></i> Ma progression
                </a>
            </div>
        </div>

    </aside>

</div>

@endsection

@section('extra-scripts')
<script>
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