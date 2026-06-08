@extends('layouts.admin')

@section('title', 'Modifier la Classe')

@section('breadcrumb')
    <a href="{{ route('admin.classes.index') }}" class="tb-bc-page" style="text-decoration:none;">Classes</a>
    <span class="tb-bc-sep">/</span>
    <a href="{{ route('admin.classes.show', $class->id) }}" class="tb-bc-page" style="text-decoration:none;">{{ $class->name }}</a>
    <span class="tb-bc-sep">/</span>
    <span class="tb-bc-current">Modifier</span>
@endsection

@section('extra-styles')
<style>
    /* ── Layout ── */
    .edit-wrapper {
        max-width: 680px;
        margin: 0 auto;
    }

    /* ── Searchable select (teacher) ── */
    .custom-select-wrap {
        position: relative;
    }

    .custom-select-display {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        background: var(--surface);
        color: var(--ink);
        cursor: pointer;
        transition: border-color .2s, box-shadow .2s;
        font-size: 13.5px;
        user-select: none;
    }

    .custom-select-display:focus-within,
    .custom-select-display.open {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-bg);
    }

    .custom-select-display i.ti-chevron-down {
        margin-left: auto;
        font-size: 13px;
        color: var(--ink-4);
        transition: transform .2s;
    }

    .custom-select-display.open i.ti-chevron-down {
        transform: rotate(180deg);
    }

    .custom-select-avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--accent-bg);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .custom-select-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        z-index: 100;
        display: none;
        overflow: hidden;
    }

    .custom-select-dropdown.visible { display: block; }

    .custom-select-search-wrap {
        padding: 8px;
        border-bottom: 1px solid var(--line);
        position: relative;
    }

    .custom-select-search-wrap i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: var(--ink-4);
    }

    .custom-select-search {
        width: 100%;
        padding: 6px 10px 6px 30px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 12.5px;
        font-family: inherit;
        background: var(--surface-2);
        color: var(--ink);
        outline: none;
    }

    .custom-select-search:focus {
        border-color: var(--accent);
    }

    .custom-select-options {
        max-height: 220px;
        overflow-y: auto;
    }

    .custom-select-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        cursor: pointer;
        font-size: 13px;
        color: var(--ink-2);
        transition: background .15s;
    }

    .custom-select-option:hover,
    .custom-select-option.focused { background: var(--surface-2); }

    .custom-select-option.selected {
        background: var(--accent-bg);
        color: var(--accent);
        font-weight: 600;
    }

    .custom-select-option .opt-name { flex: 1; }
    .custom-select-option .opt-check {
        font-size: 13px;
        color: var(--accent);
        opacity: 0;
    }
    .custom-select-option.selected .opt-check { opacity: 1; }

    .custom-select-empty {
        padding: 16px;
        text-align: center;
        font-size: 12.5px;
        color: var(--ink-4);
    }

    /* ── Student picker ── */
    .student-picker {
        border: 1px solid var(--line-2);
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--surface);
    }

    .student-picker-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        background: var(--surface-2);
        border-bottom: 1px solid var(--line);
    }

    .student-picker-search-wrap {
        position: relative;
        flex: 1;
    }

    .student-picker-search-wrap i {
        position: absolute;
        left: 9px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        color: var(--ink-4);
    }

    .student-picker-search {
        width: 100%;
        padding: 6px 10px 6px 28px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 12.5px;
        font-family: inherit;
        background: var(--surface);
        color: var(--ink);
        outline: none;
        transition: border-color .2s;
    }

    .student-picker-search:focus { border-color: var(--accent); }

    .student-picker-count {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--accent);
        background: var(--accent-bg);
        padding: 3px 10px;
        border-radius: 100px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .student-picker-actions {
        display: flex;
        gap: 6px;
    }

    .student-picker-btn {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        background: var(--surface);
        color: var(--ink-3);
        cursor: pointer;
        white-space: nowrap;
        transition: background .15s, color .15s, border-color .15s;
        font-family: inherit;
    }

    .student-picker-btn:hover {
        background: var(--surface-3);
        color: var(--ink-2);
    }

    .student-picker-btn.select-all-btn:hover {
        background: var(--accent-bg);
        color: var(--accent);
        border-color: rgba(61,90,254,.3);
    }

    .student-picker-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .student-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        cursor: pointer;
        transition: background .15s;
        border-bottom: 1px solid var(--line);
    }

    .student-item:last-child { border-bottom: none; }

    .student-item:hover { background: var(--surface-2); }

    .student-item.hidden { display: none; }

    .student-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--accent);
        flex-shrink: 0;
        cursor: pointer;
    }

    .student-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--surface-3);
        color: var(--ink-3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
        transition: background .15s, color .15s;
    }

    .student-item:has(input:checked) .student-avatar {
        background: var(--accent-bg);
        color: var(--accent);
    }

    .student-info { flex: 1; min-width: 0; }

    .student-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-email {
        font-size: 11.5px;
        color: var(--ink-4);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-check-icon {
        font-size: 14px;
        color: var(--accent);
        opacity: 0;
        transition: opacity .15s;
        flex-shrink: 0;
    }

    .student-item:has(input:checked) .student-check-icon { opacity: 1; }

    .student-picker-empty {
        padding: 2rem;
        text-align: center;
        font-size: 13px;
        color: var(--ink-4);
        display: none;
    }
</style>
@endsection

@section('content')
<div class="edit-wrapper">
    <h1 class="page-title">Modifier la Classe</h1>
    <p class="page-subtitle">Mise à jour des informations et des étudiants assignés.</p>

    <div class="card" style="padding: 28px;">
        <form method="POST" action="{{ route('admin.classes.update', $class->id) }}" id="edit-form" autocomplete="off">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="form-group">
                <label class="label" for="name">Nom de la classe</label>
                <input type="text" id="name" name="name" class="input"
                       value="{{ old('name', $class->name) }}" required autofocus autocomplete="off">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label class="label" for="description">Description (Optionnelle)</label>
                <textarea id="description" name="description" class="input" rows="3">{{ old('description', $class->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>

            {{-- Teacher — searchable custom select --}}
            <div class="form-group">
                <label class="label">Enseignant responsable</label>

                @php
                    $selectedTeacherId = old('teacher_id', $class->teacher_id);
                    $selectedTeacher   = $teachers->firstWhere('id', $selectedTeacherId);
                @endphp

                {{-- Hidden real select (submitted) --}}
                <select name="teacher_id" id="teacher_id_input" style="display:none;">
                    <option value=""></option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ $selectedTeacherId == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Custom UI --}}
                <div class="custom-select-wrap" id="teacher-select-wrap">
                    <div class="custom-select-display" id="teacher-display" tabindex="0" role="combobox">
                        @if($selectedTeacher)
                            <div class="custom-select-avatar">{{ mb_strtoupper(mb_substr($selectedTeacher->name,0,1)) }}</div>
                            <span id="teacher-label">{{ $selectedTeacher->name }}</span>
                        @else
                            <i class="ti ti-user-circle" style="font-size:18px; color:var(--ink-4);"></i>
                            <span id="teacher-label" style="color:var(--ink-4);">-- Sélectionner un enseignant --</span>
                        @endif
                        <i class="ti ti-chevron-down"></i>
                    </div>

                    <div class="custom-select-dropdown" id="teacher-dropdown">
                        <div class="custom-select-search-wrap">
                            <i class="ti ti-search"></i>
                            <input type="text" class="custom-select-search" id="teacher-search"
                                   placeholder="Rechercher un enseignant…" autocomplete="off">
                        </div>
                        <div class="custom-select-options" id="teacher-options">
                            <div class="custom-select-option {{ !$selectedTeacherId ? 'selected' : '' }}"
                                 data-value="" data-name="Aucun enseignant">
                                <i class="ti ti-user-off" style="color:var(--ink-4); font-size:16px;"></i>
                                <span class="opt-name" style="color:var(--ink-4); font-style:italic;">Aucun enseignant</span>
                                <i class="ti ti-check opt-check"></i>
                            </div>
                            @foreach($teachers as $teacher)
                                <div class="custom-select-option {{ $selectedTeacherId == $teacher->id ? 'selected' : '' }}"
                                     data-value="{{ $teacher->id }}"
                                     data-name="{{ $teacher->name }}">
                                    <div class="custom-select-avatar" style="flex-shrink:0;">
                                        {{ mb_strtoupper(mb_substr($teacher->name,0,1)) }}
                                    </div>
                                    <span class="opt-name">{{ $teacher->name }}</span>
                                    <i class="ti ti-check opt-check"></i>
                                </div>
                            @endforeach
                            <div class="custom-select-empty" id="teacher-empty" style="display:none;">
                                Aucun résultat
                            </div>
                        </div>
                    </div>
                </div>

                @error('teacher_id') <div class="error" style="margin-top:6px;">{{ $message }}</div> @enderror
            </div>

            {{-- Students — searchable picker --}}
            <div class="form-group">
                <label class="label">Gestion des étudiants</label>

                @php
                    $selectedStudents = old('students', $class->students->pluck('id')->toArray());
                @endphp

                <div class="student-picker">
                    <div class="student-picker-header">
                        <div class="student-picker-search-wrap">
                            <i class="ti ti-search"></i>
                            <input type="text" class="student-picker-search" id="student-search"
                                   placeholder="Rechercher par nom ou email…" autocomplete="off">
                        </div>
                        <span class="student-picker-count" id="selected-count">
                            {{ count($selectedStudents) }} sélectionné(s)
                        </span>
                        <div class="student-picker-actions">
                            <button type="button" class="student-picker-btn select-all-btn" id="select-all-btn">
                                Tout cocher
                            </button>
                            <button type="button" class="student-picker-btn" id="deselect-all-btn">
                                Tout décocher
                            </button>
                        </div>
                    </div>

                    <div class="student-picker-list" id="student-list">
                        @forelse($students as $student)
                            <label class="student-item"
                                   for="student_{{ $student->id }}"
                                   data-name="{{ strtolower($student->name) }}"
                                   data-email="{{ strtolower($student->email) }}">
                                <input type="checkbox"
                                       name="students[]"
                                       value="{{ $student->id }}"
                                       id="student_{{ $student->id }}"
                                       {{ in_array($student->id, $selectedStudents) ? 'checked' : '' }}>
                                <div class="student-avatar">{{ mb_strtoupper(mb_substr($student->name,0,1)) }}</div>
                                <div class="student-info">
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-email">{{ $student->email }}</div>
                                </div>
                                <i class="ti ti-check student-check-icon"></i>
                            </label>
                        @empty
                            <div style="padding:2rem; text-align:center; color:var(--ink-4); font-size:0.875rem;">
                                Aucun étudiant disponible
                            </div>
                        @endforelse
                        <div class="student-picker-empty" id="student-empty">
                            <i class="ti ti-search" style="font-size:20px; display:block; margin-bottom:6px; opacity:.4;"></i>
                            Aucun étudiant trouvé
                        </div>
                    </div>
                </div>

                @error('students') <div class="error" style="margin-top:6px;">{{ $message }}</div> @enderror
            </div>

            <div class="btn-group">
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
/* ── Teacher searchable select ── */
(function () {
    const display    = document.getElementById('teacher-display');
    const dropdown   = document.getElementById('teacher-dropdown');
    const search     = document.getElementById('teacher-search');
    const input      = document.getElementById('teacher_id_input');
    const label      = document.getElementById('teacher-label');
    const emptyMsg   = document.getElementById('teacher-empty');
    const options    = document.querySelectorAll('#teacher-options .custom-select-option');

    function openDropdown() {
        dropdown.classList.add('visible');
        display.classList.add('open');
        search.value = '';
        filterOptions('');
        search.focus();
    }

    function closeDropdown() {
        dropdown.classList.remove('visible');
        display.classList.remove('open');
    }

    function selectOption(opt) {
        const value = opt.dataset.value;
        const name  = opt.dataset.name;

        // Update hidden input
        input.value = value;

        // Update display
        if (value) {
            display.innerHTML = `
                <div class="custom-select-avatar">${name.charAt(0).toUpperCase()}</div>
                <span id="teacher-label">${name}</span>
                <i class="ti ti-chevron-down" style="margin-left:auto; font-size:13px; color:var(--ink-4);"></i>`;
        } else {
            display.innerHTML = `
                <i class="ti ti-user-circle" style="font-size:18px; color:var(--ink-4);"></i>
                <span id="teacher-label" style="color:var(--ink-4);">-- Sélectionner un enseignant --</span>
                <i class="ti ti-chevron-down" style="margin-left:auto; font-size:13px; color:var(--ink-4);"></i>`;
        }

        // Mark selected
        options.forEach(o => o.classList.toggle('selected', o === opt));

        closeDropdown();
    }

    function filterOptions(query) {
        let visible = 0;
        options.forEach(opt => {
            const name = (opt.dataset.name || '').toLowerCase();
            const show = !query || name.includes(query.toLowerCase());
            opt.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        emptyMsg.style.display = visible === 0 ? 'block' : 'none';
    }

    display.addEventListener('click', () => {
        dropdown.classList.contains('visible') ? closeDropdown() : openDropdown();
    });

    display.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openDropdown(); }
        if (e.key === 'Escape') closeDropdown();
    });

    search.addEventListener('input', () => filterOptions(search.value));

    options.forEach(opt => {
        opt.addEventListener('click', () => selectOption(opt));
    });

    document.addEventListener('click', e => {
        if (!document.getElementById('teacher-select-wrap').contains(e.target)) {
            closeDropdown();
        }
    });
})();

/* ── Student picker ── */
(function () {
    const searchInput    = document.getElementById('student-search');
    const items          = document.querySelectorAll('#student-list .student-item');
    const countBadge     = document.getElementById('selected-count');
    const emptyMsg       = document.getElementById('student-empty');
    const selectAllBtn   = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');

    function updateCount() {
        const checked = document.querySelectorAll('#student-list input[type="checkbox"]:checked').length;
        countBadge.textContent = checked + ' sélectionné(s)';
    }

    function filterStudents(query) {
        let visible = 0;
        items.forEach(item => {
            const name  = item.dataset.name  || '';
            const email = item.dataset.email || '';
            const show  = !query || name.includes(query) || email.includes(query);
            item.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        emptyMsg.style.display = (visible === 0 && items.length > 0) ? 'block' : 'none';
    }

    searchInput.addEventListener('input', () => {
        filterStudents(searchInput.value.toLowerCase());
    });

    document.querySelectorAll('#student-list input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    selectAllBtn.addEventListener('click', () => {
        items.forEach(item => {
            if (!item.classList.contains('hidden')) {
                item.querySelector('input[type="checkbox"]').checked = true;
            }
        });
        updateCount();
    });

    deselectAllBtn.addEventListener('click', () => {
        items.forEach(item => {
            item.querySelector('input[type="checkbox"]').checked = false;
        });
        updateCount();
    });
})();
</script>
@endsection