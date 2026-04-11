@extends('layouts.student')

@section('title', 'Mes TP')
@section('page-title', 'Travaux Pratiques Disponibles')

@section('extra-styles')
<style>
    .tps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    .tp-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .tp-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .tp-card.submitted {
        border-left: 4px solid #28a745;
    }
    .tp-card.pending {
        border-left: 4px solid #007bff;
    }
    .tp-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .tp-description {
        color: #666;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    .tp-meta {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #666;
    }
    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }
    .status-todo {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-submitted {
        background-color: #d4edda;
        color: #155724;
    }
    .btn {
        display: block;
        width: 100%;
        padding: 0.75rem;
        text-align: center;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        transition: all 0.3s;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .btn-success {
        background-color: #28a745;
        color: white;
    }
    .btn-success:hover {
        background-color: #218838;
    }
</style>
@endsection

@section('content')
    @if($tps->count() > 0)
        <div class="tps-grid">
            @foreach($tps as $tp)
                @php
                    $submission = $tp->submissions->where('student_id', Auth::id())->first();
                @endphp
                
                <div class="tp-card {{ $submission ? 'submitted' : 'pending' }}"
                     onclick="window.location.href='{{ route('student.tps.show', $tp->id) }}'"
                     style="cursor: pointer;">
                    <div class="tp-title">{{ $tp->title }}</div>
                    
                    <div class="tp-description">
                        {{ Str::limit($tp->description, 100) }}
                    </div>

                    @if($submission)
                        <span class="status-badge status-submitted">✓ Soumis</span>
                    @else
                        <span class="status-badge status-todo">À faire</span>
                    @endif

                    <div class="tp-meta">
                        <div>👨‍🏫 Enseignant: {{ $tp->teacher->name }}</div>
                        @if($tp->class)
                            <div>👥 Classe: {{ $tp->class->name }}</div>
                        @endif
                       <p>📅 Échéance: {{ $tp->due_date ? $tp->due_date->format('d/m/Y à H:i') : 'Non définie' }}</p>
                    </div>

                    @if($submission)
                        <a href="{{ route('student.tps.show', $tp->id) }}" class="btn btn-success" onclick="event.stopPropagation();">
                            👁️ Voir ma soumission
                        </a>
                    @else
                        <a href="{{ route('student.tps.show', $tp->id) }}" class="btn btn-primary" onclick="event.stopPropagation();">
                            🖊️ Voir et soumettre
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 3rem; color: #999;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
            <h2>Aucun TP disponible</h2>
            <p style="margin-top: 1rem;">Les travaux pratiques apparaîtront ici une fois publiés par vos enseignants.</p>
        </div>
    @endif
@endsection