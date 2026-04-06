@extends('layouts.student')

@section('title', 'Ma Progression')
@section('page-title', 'Ma Progression Académique')

@section('extra-styles')
<style>
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 0.5rem;
    }
    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }
    .progress-bar {
        width: 100%;
        height: 30px;
        background: #e9ecef;
        border-radius: 15px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        transition: width 0.3s;
    }
    .course-progress {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .course-progress h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .course-teacher {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    .tp-list {
        display: grid;
        gap: 0.5rem;
    }
    .tp-item {
        display: grid;
        grid-template-columns: 1fr auto auto;
        gap: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 4px;
        align-items: center;
    }
    .tp-name {
        color: #333;
        font-weight: 500;
    }
    .tp-status {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .tp-status.done {
        background: #d4edda;
        color: #155724;
    }
    .tp-status.pending {
        background: #fff3cd;
        color: #856404;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 8px;
        color: #999;
    }
</style>
@endsection

@section('content')
    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-number">{{ $totalTPs }}</div>
            <div class="stat-label">TP Total</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-number">{{ $submittedTPs }}</div>
            <div class="stat-label">TP Soumis</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-number">{{ $gradedTPs }}</div>
            <div class="stat-label">TP Notés</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-number">{{ $averageGrade }}</div>
            <div class="stat-label">Moyenne /20</div>
        </div>
    </div>

    <div class="stat-card">
        <div style="text-align: left;">
            <strong>Taux de Complétion</strong>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $completionRate }}%;">
                    {{ $completionRate }}%
                </div>
            </div>
        </div>
    </div>

    <h2 style="margin: 2rem 0 1rem 0; color: #333;">Progression par Cours</h2>

    @forelse($courses as $course)
        <div class="course-progress">
            <h3>{{ $course->name }}</h3>
            <div class="course-teacher">👨‍🏫 {{ $course->teacher->name }}</div>

            @if($course->tps->count() > 0)
                <div class="tp-list">
                    @foreach($course->tps as $tp)
                        @php
                            $submission = App\Models\Submission::where('tp_id', $tp->id)
                                ->where('student_id', Auth::id())
                                ->first();
                        @endphp
                        <div class="tp-item">
                            <div class="tp-name">{{ $tp->title }}</div>
                            @if($submission)
                                @if($submission->grade)
                                    <span class="tp-status done">Note: {{ $submission->grade }}/20</span>
                                @else
                                    <span class="tp-status done">✓ Soumis</span>
                                @endif
                            @else
                                <span class="tp-status pending">À faire</span>
                            @endif
                            <a href="{{ route('student.tps.show', $tp->id) }}" style="color: #007bff; font-size: 0.85rem;">
                                Voir →
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #999; text-align: center; padding: 1rem;">Aucun TP disponible pour ce cours</p>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📊</div>
            <h2>Aucune donnée de progression</h2>
            <p>Inscrivez-vous à des cours pour voir votre progression</p>
        </div>
    @endforelse
@endsection