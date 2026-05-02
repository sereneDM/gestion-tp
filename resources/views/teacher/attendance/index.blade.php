@extends('layouts.app')

@section('title', 'Gestion de la Présence')
@section('page-title', 'Gestion de la Présence')

@section('extra-styles')
<style>
    .form-container {
        background: var(--tp-bg-raised);
        padding: 2rem;
        border-radius: 1rem;
        max-width: 800px;
        border: 1px solid var(--tp-border);
    }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: var(--tp-text-secondary); font-weight: bold; }
    select, input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--tp-input-border);
        border-radius: 0.75rem;
        font-size: 1rem;
        background: var(--tp-input-bg);
        color: var(--tp-text-primary);
        box-sizing: border-box;
    }
    select option { background: var(--tp-input-bg); color: var(--tp-text-primary); }
    select:focus, input:focus { outline: none; border-color: #6366f1; }
    .btn {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        transition: opacity 0.15s;
    }
    .btn:hover { opacity: 0.9; }
    .btn-primary  { background: var(--tp-accent); color: white; font-size: 1rem; width: 100%; }
    .btn-secondary { background: var(--tp-table-header); color: var(--tp-text-secondary); }
    h2 { margin-bottom: 1.5rem; color: var(--tp-text-primary); }
</style>
@endsection

@section('content')

    <div class="form-container">
        <h2>Prendre les présences</h2>

        <form method="GET" action="{{ route('teacher.attendance.show') }}">
            <div class="form-group">
                <label for="class_id">Sélectionner un cours *</label>
                <select id="class_id" name="class_id" required>
                    <option value="">-- Choisir un cours --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">
                            {{ $class->name }} ({{ $class->students->count() }} étudiants)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date *</label>
                <input type="date"
                       id="date"
                       name="date"
                       value="{{ date('Y-m-d') }}"
                       required>
            </div>

            <button type="submit" class="btn btn-primary">
                Continuer →
            </button>
        </form>
    </div>
@endsection