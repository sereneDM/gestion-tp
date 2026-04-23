@extends('layouts.app')

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
        background: #0f172a;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        text-align: center;
        transition: transform 0.2s;
        border: 1px solid #334155;
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
        color: #a5b4fc;
        margin-bottom: 0.5rem;
    }
    .stat-label {
        color: #cbd5e1;
        font-size: 1rem;
    }
    .quick-actions {
        background: #0f172a;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        margin-bottom: 2rem;
        border: 1px solid #334155;
    }
    .quick-actions h2 {
        margin-top: 0;
        color: #f1f5f9;
        margin-bottom: 1.5rem;
    }
    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .action-btn {
        padding: 1rem;
        background: #4f46e5;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        text-align: center;
        transition: all 0.3s;
        font-weight: bold;
    }
    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.4);
        background: #4338ca;
    }
    .action-btn-alt {
        background: #6366f1;
    }
    .action-btn-alt:hover {
        background: #4f46e5;
    }
    /* Post styles are now in posts.css */
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
    <h2 style="margin-bottom: 1.5rem; color: #f1f5f9;">📰 Fil d'actualité</h2>

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
            <div class="post-content" style="white-space: pre-line;">{{ $post->content }}</div>

            @if($post->tp && $post->tp->due_date)
                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #334155; color: #cbd5e1; font-size: 0.9rem;">
                    📅 Échéance: {{ $post->tp->due_date->format('d/m/Y à H:i') }}
                </div>
            @endif

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
        <div style="background: #1e293b; padding: 1.5rem; border-radius: 0 0 8px 8px; border: 1px solid #334155; border-top: none;">
            {{ $posts->links() }}
        </div>
    @endif
</div>

@endsection