@extends('layouts.teacher')

@section('title', 'Gestion de la Présence')
@section('page-title', 'Gestion de la Présence')

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
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
        font-size: 1rem;
        width: 100%;
    }
    .header-actions {
        margin-bottom: 1.5rem;
        text-align: right;
    }
    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 800px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: bold;
    }
    select, input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    select:focus, input:focus {
        outline: none;
        border-color: #007bff;
    }
</style>
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