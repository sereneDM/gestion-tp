@extends('layouts.teacher')

@section('title', 'Mes TP')
@section('page-title', 'Mes Travaux Pratiques')

@section('extra-styles')
<style>
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
    }
    .btn-primary { background-color: #007bff; color: white; }
    .btn-secondary { background-color: #6c757d; color: white; }
    .btn-info { background-color: #17a2b8; color: white; }
    .btn-warning { background-color: #ffc107; color: #333; }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-small { padding: 0.4rem 0.8rem; font-size: 0.85rem; }
    .header-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
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
    .tp-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 0.5rem;
    }
    .tp-description {
        color: #666;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .tp-meta {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 0.5rem;
    }
    .status-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }
    .status-draft { background-color: #ffc107; color: #333; }
    .status-published { background-color: #28a745; color: white; }
    .status-closed { background-color: #dc3545; color: white; }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .delete-form { display: inline; }
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
    {{ Breadcrumbs::render('teacher.courses.index') }}
    <div class="header-actions">
        <a href="{{ route('teacher.tps.create') }}" class="btn btn-primary">
            ➕ Créer un TP
        </a>
    </div>

    @if($tps->count() > 0)
        <div class="tps-grid">
            @foreach($tps as $tp)
                <div class="tp-card"
                     onclick="window.location.href='{{ route('teacher.tps.show', $tp->id) }}'"
                     style="cursor: pointer;">
                    <div class="tp-title">{{ $tp->title }}</div>

                    <span class="status-badge status-{{ $tp->status }}">
                        {{ ucfirst($tp->status) }}
                    </span>

                    <div class="tp-description">{{ $tp->description }}</div>

                    📅 Échéance: {{ $tp->due_date ? $tp->due_date->format('d/m/Y à H:i') : 'Non définie' }}
                    <div class="tp-meta">
                        👥 Classe: {{ $tp->class ? $tp->class->name : 'Toutes les classes' }}
                    </div>
                    <div class="tp-meta">
                        📊 Soumissions: {{ $tp->submissions->count() }}
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('teacher.tps.show', $tp->id) }}" class="btn btn-info btn-small" onclick="event.stopPropagation();">
                            👁️ Voir détails
                        </a>
                        <a href="{{ route('teacher.tps.edit', $tp->id) }}" class="btn btn-warning btn-small" onclick="event.stopPropagation();">
                            ✏️ Modifier
                        </a>
                        <form method="POST"
                              action="{{ route('teacher.tps.destroy', $tp->id) }}"
                              class="delete-form"
                              onsubmit="return confirm('Supprimer ce TP?')"
                              onclick="event.stopPropagation();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-small">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <h2>Aucun TP créé</h2>
            <p>Cliquez sur "Créer un TP" pour commencer</p>
        </div>
    @endif
@endsection