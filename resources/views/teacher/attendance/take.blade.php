@extends('layouts.teacher')

@section('title', 'Prendre les Présences')
@section('page-title', 'Présence - ' . $class->name)

@section('extra-styles')
<style>
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 1rem;
        display: inline-block;
    }
    .btn-primary {
        background-color: #28a745;
        color: white;
        width: 100%;
    }
    .date-info {
        color: #666;
        margin-bottom: 1.5rem;
    }
    .form-container {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .student-row {
        display: grid;
        grid-template-columns: 2fr 1fr 2fr;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #ddd;
        align-items: center;
    }
    .student-row:hover {
        background-color: #f8f9fa;
    }
    .student-name {
        font-weight: bold;
        color: #333;
    }
    .status-buttons {
        display: flex;
        gap: 0.5rem;
    }
    .status-btn {
        padding: 0.5rem 1rem;
        border: 2px solid #ddd;
        border-radius: 4px;
        background: white;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .status-btn.active {
        font-weight: bold;
    }
    .status-btn[data-status="present"].active {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }
    .status-btn[data-status="absent"].active {
        background-color: #dc3545;
        color: white;
        border-color: #dc3545;
    }
    .status-btn[data-status="late"].active {
        background-color: #ffc107;
        color: #333;
        border-color: #ffc107;
    }
    .status-btn[data-status="excused"].active {
        background-color: #17a2b8;
        color: white;
        border-color: #17a2b8;
    }
    input[type="text"] {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    input[type="radio"] {
        display: none;
    }
</style>
@endsection

@section('content')
    <p class="date-info">Date: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>

    <div class="form-container">
        <form method="POST" action="{{ route('teacher.attendance.save') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class->id }}">
            <input type="hidden" name="date" value="{{ $date }}">

            @foreach($class->students as $student)
                @php
                    $existing = $existingAttendances->get($student->id);
                    $currentStatus = $existing ? $existing->status : 'present';
                @endphp

                <div class="student-row">
                    <div class="student-name">{{ $student->name }}</div>

                    <div class="status-buttons">
                        <input type="radio"
                               name="attendance[{{ $student->id }}]"
                               value="present"
                               id="present_{{ $student->id }}"
                               {{ $currentStatus === 'present' ? 'checked' : '' }}>
                        <label for="present_{{ $student->id }}"
                               class="status-btn {{ $currentStatus === 'present' ? 'active' : '' }}"
                               data-status="present"
                               onclick="selectStatus({{ $student->id }}, 'present')">
                            ✓
                        </label>

                        <input type="radio"
                               name="attendance[{{ $student->id }}]"
                               value="absent"
                               id="absent_{{ $student->id }}"
                               {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                        <label for="absent_{{ $student->id }}"
                               class="status-btn {{ $currentStatus === 'absent' ? 'active' : '' }}"
                               data-status="absent"
                               onclick="selectStatus({{ $student->id }}, 'absent')">
                            ✗
                        </label>

                        <input type="radio"
                               name="attendance[{{ $student->id }}]"
                               value="late"
                               id="late_{{ $student->id }}"
                               {{ $currentStatus === 'late' ? 'checked' : '' }}>
                        <label for="late_{{ $student->id }}"
                               class="status-btn {{ $currentStatus === 'late' ? 'active' : '' }}"
                               data-status="late"
                               onclick="selectStatus({{ $student->id }}, 'late')">
                            ⏰
                        </label>

                        <input type="radio"
                               name="attendance[{{ $student->id }}]"
                               value="excused"
                               id="excused_{{ $student->id }}"
                               {{ $currentStatus === 'excused' ? 'checked' : '' }}>
                        <label for="excused_{{ $student->id }}"
                               class="status-btn {{ $currentStatus === 'excused' ? 'active' : '' }}"
                               data-status="excused"
                               onclick="selectStatus({{ $student->id }}, 'excused')">
                            📝
                        </label>
                    </div>

                    <div>
                        <input type="text"
                               name="notes[{{ $student->id }}]"
                               placeholder="Notes (optionnel)"
                               value="{{ $existing ? $existing->notes : '' }}">
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    ✓ Enregistrer les présences
                </button>
            </div>
        </form>
    </div>
@endsection

@section('extra-scripts')
<script>
    function selectStatus(studentId, status) {
        const studentRow = document.querySelector(`#present_${studentId}`).closest('.student-row');
        studentRow.querySelectorAll('.status-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const selectedBtn = studentRow.querySelector(`.status-btn[data-status="${status}"]`);
        selectedBtn.classList.add('active');
        document.querySelector(`#${status}_${studentId}`).checked = true;
    }
</script>
@endsection