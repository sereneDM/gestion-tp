@extends('layouts.app')

@section('title', 'Prendre les Présences')
@section('page-title', 'Présence — ' . $class->name)

@section('breadcrumbs')
    {{ Breadcrumbs::render('teacher.attendance.show', $class, $date) }}
@endsection

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
    --purple-bg:  #f3f0ff;
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,0.07);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 780px; margin: 0 auto; padding: 0.5rem 0 3rem; }

/* ── Date pill ── */
.date-pill {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.4rem 0.9rem;
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 100px;
    font-size: 0.82rem; color: var(--ink-2);
    margin-bottom: 1.25rem;
}
.date-pill i { font-size: 14px; color: var(--ink-4); }

/* ── Card ── */
.card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

/* ── Student rows ── */
.student-row {
    display: grid;
    grid-template-columns: 1fr auto 200px;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--line);
    align-items: center;
    transition: background 0.15s;
}
.student-row:last-of-type { border-bottom: none; }
.student-row:hover { background: var(--surface-2); }

.student-name { font-size: 0.9rem; font-weight: 600; color: var(--ink); }

/* ── Status buttons ── */
.status-buttons { display: flex; gap: 0.35rem; }

input[type="radio"] { display: none; }

.status-btn {
    padding: 0.35rem 0.7rem;
    border: 1.5px solid var(--line-2);
    border-radius: var(--radius-sm);
    background: var(--surface-2);
    cursor: pointer;
    font-size: 0.78rem; font-weight: 600;
    color: var(--ink-3);
    transition: all 0.15s;
    white-space: nowrap;
    display: inline-flex; align-items: center; gap: 4px;
    font-family: var(--font-body);
}
.status-btn:hover { border-color: var(--line); background: var(--surface-3); }

.status-btn[data-status="present"].active {
    background: var(--success-bg);
    color: var(--success);
    border-color: rgba(16,185,129,0.3);
}
.status-btn[data-status="absent"].active {
    background: var(--danger-bg);
    color: var(--danger);
    border-color: rgba(229,57,53,0.3);
}
.status-btn[data-status="late"].active {
    background: var(--warning-bg);
    color: var(--warning);
    border-color: rgba(245,158,11,0.3);
}
.status-btn[data-status="excused"].active {
    background: var(--purple-bg);
    color: var(--purple);
    border-color: rgba(124,58,237,0.3);
}

/* ── Notes input ── */
.note-input {
    width: 100%;
    padding: 0.45rem 0.75rem;
    border: 1px solid var(--line-2);
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-family: var(--font-body);
    background: var(--surface);
    color: var(--ink);
    transition: border-color 0.2s;
}
.note-input::placeholder { color: var(--ink-4); }
.note-input:focus { outline: none; border-color: var(--accent); }

/* ── Footer ── */
.card-footer {
    padding: 1.1rem 1.5rem;
    border-top: 1px solid var(--line);
    background: var(--surface-2);
}
.btn-submit {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.7rem 1.6rem;
    border-radius: var(--radius-md); border: none;
    background: var(--accent); color: white;
    font-size: 0.875rem; font-weight: 700;
    font-family: var(--font-body); cursor: pointer;
    box-shadow: 0 2px 8px rgba(61,90,254,0.3);
    transition: background 0.2s, transform 0.15s;
}
.btn-submit i { font-size: 15px; }
.btn-submit:hover { background: var(--accent-2); transform: translateY(-1px); }

@media (max-width: 640px) {
    .student-row { grid-template-columns: 1fr; gap: 0.6rem; }
}
</style>
@endsection

@section('content')
<div class="page-wrapper">

    <div class="date-pill">
        <i class="ti ti-calendar"></i>
        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
    </div>

    <div class="card">
        <form method="POST" action="{{ route('teacher.attendance.save') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class->id }}">
            <input type="hidden" name="date" value="{{ $date }}">

            @if(request('student_id'))
                <input type="hidden" name="student_id" value="{{ request('student_id') }}">
            @endif

            @foreach($class->students as $classStudent)
                @php
                    $existing      = $existingAttendances->get($classStudent->id);
                    $currentStatus = $existing ? $existing->status : 'present';
                @endphp

                <div class="student-row">
                    <div class="student-name">{{ $classStudent->name }}</div>

                    <div class="status-buttons">
                        @foreach(['present' => ['ti-check', 'Présent'], 'absent' => ['ti-x', 'Absent'], 'late' => ['ti-clock', 'Retard'], 'excused' => ['ti-file-text', 'Excusé']] as $status => [$icon, $label])
                            <input type="radio"
                                   name="attendance[{{ $classStudent->id }}]"
                                   value="{{ $status }}"
                                   id="{{ $status }}_{{ $classStudent->id }}"
                                   {{ $currentStatus === $status ? 'checked' : '' }}>
                            <label for="{{ $status }}_{{ $classStudent->id }}"
                                   class="status-btn {{ $currentStatus === $status ? 'active' : '' }}"
                                   data-status="{{ $status }}"
                                   onclick="selectStatus({{ $classStudent->id }}, '{{ $status }}')">
                                <i class="ti {{ $icon }}"></i> {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <input type="text"
                           class="note-input"
                           name="notes[{{ $classStudent->id }}]"
                           placeholder="Note (optionnel)"
                           value="{{ $existing ? $existing->notes : '' }}">
                </div>
            @endforeach

            <div class="card-footer">
                <button type="submit" class="btn-submit">
                    <i class="ti ti-device-floppy"></i> Enregistrer les présences
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@section('extra-scripts')
<script>
function selectStatus(studentId, status) {
    const row = document.querySelector(`#present_${studentId}`).closest('.student-row');
    row.querySelectorAll('.status-btn').forEach(btn => btn.classList.remove('active'));
    row.querySelector(`.status-btn[data-status="${status}"]`).classList.add('active');
    document.querySelector(`#${status}_${studentId}`).checked = true;
}
</script>
@endsection