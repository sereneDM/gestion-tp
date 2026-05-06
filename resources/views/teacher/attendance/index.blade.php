@extends('layouts.app')

@section('title', 'Gestion de la Présence')
@section('page-title', 'Gestion de la Présence')

@section('extra-styles')
<style>
    .btn {
        padding: 0.65rem 1.5rem;
        border: 1px solid #334155;
        border-radius: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-block;
        color: #e2e8f0;
        background: #1e293b;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn:hover {
        background: #334155;
        border-color: #475569;
    }
    .btn-primary {
        background: #1e293b;
        color: #e2e8f0;
        width: 100%;
    }
    .btn-primary:hover {
        background: #334155;
    }
    .btn-secondary {
        background: #1e293b;
        color: #e2e8f0;
    }
    .btn-secondary:hover {
        background: #334155;
    }
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
    }
    .form-container {
        background: #0f172a;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 12px 24px rgba(15,23,42,0.25);
        max-width: 600px;
        margin: 0 auto;
        border: 1px solid #334155;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #cbd5e1;
        font-weight: bold;
    }
    select, input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #475569;
        border-radius: 0.75rem;
        font-size: 1rem;
        background: #1e293b;
        color: #e2e8f0;
    }
    select:focus, input:focus {
        outline: none;
        border-color: #6366f1;
    }
</style>
@endsection
@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.attendance.index') }}
@endsection
@section('content')
 

    <div class="form-container">
        <h2 style="margin-bottom: 1.5rem;">Prendre les présences</h2>

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