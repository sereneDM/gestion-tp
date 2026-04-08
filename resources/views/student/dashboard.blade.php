@extends('layouts.student')

@section('title', 'Accueil')
@section('page-title', 'Accueil')

@section('extra-styles')
<style>
    /* (UNCHANGED CSS — exactly as you gave it) */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 0.5rem;
    }
    .stat-label {
        color: #666;
        font-size: 1rem;
    }
    .quick-actions {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .quick-actions h2 {
        margin-top: 0;
        color: #333;
        margin-bottom: 1.5rem;
    }
    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .action-btn {
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        text-align: center;
        transition: all 0.3s;
        font-weight: bold;
    }
    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .action-btn-alt {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .feed-section {
        margin-top: 2rem;
    }
    .feed-header {
        background: white;
        padding: 1.5rem;
        border-radius: 8px 8px 0 0;
        border-bottom: 2px solid #f0f0f0;
    }
    .feed-header h2 {
        margin: 0;
        color: #333;
    }
    .post-card {
        background: white;
        padding: 2rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }
    .post-card:hover {
        background: #f8f9fa;
    }
    .post-card:last-child {
        border-bottom: none;
        border-radius: 0 0 8px 8px;
    }
    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    .post-author {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .author-info {
        flex: 1;
    }
    .author-name {
        font-weight: bold;
        color: #333;
        margin-bottom: 0.25rem;
    }
    .post-meta {
        font-size: 0.85rem;
        color: #666;
    }
    .post-type-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .type-announcement { background: #fff3cd; color: #856404; }
    .type-tp_posted { background: #d4edda; color: #155724; }
    .type-reminder { background: #f8d7da; color: #721c24; }
    .type-general { background: #d1ecf1; color: #0c5460; }
    .post-title { font-size: 1.3rem; font-weight: bold; color: #333; margin-bottom: 0.5rem; }
    .post-content { color: #666; line-height: 1.6; margin-bottom: 1rem; }
    .post-course {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #e7f3ff;
        border-left: 4px solid #007bff;
        border-radius: 4px;
        margin-top: 1rem;
        font-size: 0.9rem;
    }
    .post-attachment { margin-top: 1rem; }
    .attachment-btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .attachment-btn:hover { background: #0056b3; }
    .no-posts {
        background: white;
        padding: 3rem;
        text-align: center;
        color: #999;
        border-radius: 0 0 8px 8px;
    }
</style>
@endsection

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-number">{{ $enrolledCoursesCount }}</div>
        <div class="stat-label">Cours suivis</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-number">{{ $availableTPs }}</div>
        <div class="stat-label">TP disponibles</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-number">{{ $submittedCount }}</div>
        <div class="stat-label">TP soumis</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-number">{{ $gradedCount }}</div>
        <div class="stat-label">TP notés</div>
    </div>
</div>

<div class="quick-actions">
    <h2>🚀 Actions rapides</h2>
    <div class="action-buttons">
        <a href="{{ route('student.join-course.form') }}" class="action-btn">➕ Rejoindre un cours</a>
        <a href="{{ route('student.my-courses') }}" class="action-btn action-btn-alt">📚 Voir mes cours</a>
        <a href="{{ route('student.submissions.index') }}" class="action-btn">📄 Mes soumissions</a>
        <a href="{{ route('student.progress') }}" class="action-btn action-btn-alt">📊 Ma progression</a>
    </div>
</div>

<div class="feed-section">
    <div class="feed-header">
        <h2>📰 Fil d'actualité</h2>
    </div>

    @forelse($posts as $post)
        <div class="post-card" onclick="window.location='{{ route('posts.show', $post->id) }}'" style="cursor:pointer;">

            <div class="post-header">
                <div class="post-author">
                    <img src="{{ $post->user->profile_picture_url }}"
                         alt="{{ $post->user->name }}"
                         style="width:50px;height:50px;border-radius:50%;object-fit:cover;">

                    <div class="author-info">
                        <div class="author-name">{{ $post->user->name }}</div>
                        <div class="post-meta">
                            {{ $post->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <span class="post-type-badge type-{{ $post->type }}">
                    @if($post->type === 'announcement') 📢 Annonce
                    @elseif($post->type === 'tp_posted') 📝 Nouveau TP
                    @elseif($post->type === 'reminder') ⏰ Rappel
                    @else 📌 Général
                    @endif
                </span>
            </div>

            <div class="post-title">{{ $post->title }}</div>
            <div class="post-content">{{ $post->content }}</div>

            @if($post->class)
                <div class="post-course">📚 {{ $post->class->name }}</div>
            @endif

            @if($post->tp)
                <div style="margin-top: 1rem;">
                    <a href="{{ route('student.tps.show', $post->tp->id) }}" class="attachment-btn">
                        👁️ Voir le TP
                    </a>
                </div>
            @endif

            @if($post->attachment)
                <div class="post-attachment">
                    <a href="{{ asset('storage/' . $post->attachment) }}" target="_blank" class="attachment-btn">
                        📎 Télécharger la pièce jointe
                    </a>
                </div>
            @endif

        </div>
    @empty
        <div class="no-posts">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
            <h3>Aucune publication</h3>
            <p>Les annonces de vos enseignants apparaîtront ici</p>
        </div>
    @endforelse

    @if($posts->hasPages())
        <div style="background: white; padding: 1.5rem; border-radius: 0 0 8px 8px;">
            {{ $posts->links() }}
        </div>
    @endif
</div>

@endsection