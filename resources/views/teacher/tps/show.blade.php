@extends('layouts.app')

@section('title', Str::limit($tp->title, 50))
@section('page-title', Str::limit($tp->title, 50))

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
    --radius-sm:  6px;
    --radius-md:  10px;
    --radius-lg:  16px;
    --radius-xl:  22px;
    --shadow-sm:  0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
    --shadow-md:  0 4px 16px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    --font-body:  'DM Sans', sans-serif;
    --font-serif: 'DM Serif Display', serif;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body); background: var(--surface-2); color: var(--ink); }

.page-wrapper { max-width: 1000px; margin: 0 auto; padding: 0.5rem 0 3rem; display: flex; flex-direction: column; gap: 1.25rem; }

/* ── Card ── */
.card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.75rem;
    border-bottom: 1px solid var(--line);
}
.card-header-title {
    display: flex; align-items: center; gap: 0.6rem;
    font-size: 0.9rem; font-weight: 700; color: var(--ink);
}
.card-header-title i { font-size: 17px; color: var(--ink-3); }

.card-body { padding: 1.5rem 1.75rem; }

/* ── Info rows ── */
.info-row {
    display: grid;
    grid-template-columns: 180px 1fr;
    gap: 1rem;
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--line);
    align-items: start;
}
.info-row:last-child { border-bottom: none; }

.info-label {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--ink-4);
    padding-top: 0.1rem;
}

.info-value {
    font-size: 0.875rem; color: var(--ink-2);
    line-height: 1.6; word-break: break-word;
}
.info-value.desc-value {
    white-space: pre-wrap;
    max-height: 8rem;
    overflow-y: auto;
}

/* ── Badges ── */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0.2rem 0.65rem;
    border-radius: 100px;
    font-size: 0.72rem; font-weight: 700;
}
.badge i { font-size: 11px; }
.badge-published { background: var(--success-bg); color: var(--success); }
.badge-draft     { background: var(--warning-bg); color: var(--warning); }
.badge-closed    { background: var(--danger-bg);  color: var(--danger);  }
.badge-graded    { background: var(--success-bg); color: var(--success); }
.badge-pending   { background: var(--warning-bg); color: var(--warning); }

.attachment-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 0.35rem 0.9rem;
    background: var(--surface-2); border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    color: var(--ink-2); font-size: 0.8rem; font-weight: 500;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
}
.attachment-btn i { font-size: 14px; }
.attachment-btn:hover { background: var(--surface-3); border-color: var(--line-2); }

/* ── Submissions count badge ── */
.count-badge {
    font-size: 0.72rem; font-weight: 700;
    color: var(--ink-4);
    background: var(--surface-2);
    border: 1px solid var(--line);
    border-radius: 100px;
    padding: 0.1rem 0.5rem;
}

/* ── Table ── */
.table-wrap { overflow-x: auto; }

.submissions-table { width: 100%; border-collapse: collapse; }
.submissions-table thead { background: var(--surface-2); }
.submissions-table th {
    padding: 0.85rem 1.25rem;
    text-align: left;
    font-size: 0.7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em;
    color: var(--ink-4);
    border-bottom: 1px solid var(--line);
    white-space: nowrap;
}
.submissions-table td {
    padding: 0.9rem 1.25rem;
    font-size: 0.875rem; color: var(--ink-2);
    border-bottom: 1px solid var(--line);
}
.submissions-table tbody tr:last-child td { border-bottom: none; }
.submissions-table tbody tr:hover { background: var(--surface-2); }

.grade-chip {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 0.2rem 0.65rem;
    border-radius: 100px;
    font-size: 0.78rem; font-weight: 700;
}
.grade-good    { background: var(--success-bg); color: var(--success); }
.grade-average { background: var(--warning-bg); color: var(--warning); }
.grade-poor    { background: var(--danger-bg);  color: var(--danger);  }

.btn-view {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-sm);
    background: var(--accent-bg);
    border: 1px solid rgba(61,90,254,0.2);
    color: var(--accent);
    font-size: 0.78rem; font-weight: 600;
    text-decoration: none;
    transition: background 0.15s;
}
.btn-view:hover { background: rgba(61,90,254,0.15); }
.btn-view i { font-size: 13px; }

/* ── Empty state ── */
.empty-state {
    text-align: center; padding: 3.5rem 2rem; color: var(--ink-3);
}
.empty-icon {
    width: 56px; height: 56px; border-radius: 16px;
    background: var(--surface-2); border: 1px solid var(--line);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; font-size: 24px; color: var(--ink-4);
}
.empty-state h3 { font-size: 0.95rem; font-weight: 600; color: var(--ink-2); margin-bottom: 0.3rem; }
.empty-state p  { font-size: 0.83rem; }

/* scrollbar */
.info-value.desc-value::-webkit-scrollbar { width: 4px; }
.info-value.desc-value::-webkit-scrollbar-thumb { background: var(--line-2); border-radius: 4px; }
</style>
@endsection

@section('content')
<div class="page-wrapper">

    {{-- ── TP Info card ── --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="ti ti-file-text"></i> Informations du TP
            </div>
            <a href="{{ route('teacher.tps.edit', $tp->id) }}" class="attachment-btn">
                <i class="ti ti-edit"></i> Modifier
            </a>
        </div>
        <div class="card-body">
            <div class="info-row">
                <div class="info-label">Cours</div>
                <div class="info-value">{{ $tp->class->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Titre</div>
                <div class="info-value" style="font-weight:600;color:var(--ink);">{{ $tp->title }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Description</div>
                <div class="info-value desc-value">{{ $tp->description }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Échéance</div>
                <div class="info-value">
                    @if($tp->due_date)
                        <span style="display:inline-flex;align-items:center;gap:0.4rem;">
                            <i class="ti ti-calendar-due" style="font-size:14px;color:var(--ink-4);"></i>
                            {{ $tp->due_date->format('d/m/Y à H:i') }}
                        </span>
                    @else
                        <span style="color:var(--ink-4);font-style:italic;">Pas d'échéance définie</span>
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    <span class="badge badge-{{ $tp->status }}">
                        @if($tp->status === 'published') <i class="ti ti-circle-check"></i> Publié
                        @elseif($tp->status === 'draft')  <i class="ti ti-pencil"></i> Brouillon
                        @else                             <i class="ti ti-lock"></i> Fermé
                        @endif
                    </span>
                </div>
            </div>
            @if($tp->attachments)
                <div class="info-row">
                    <div class="info-label">Fichier joint</div>
                    <div class="info-value" style="display:flex; flex-direction:column; gap:0.5rem; align-items:flex-start;">
                        @foreach((array)$tp->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="attachment-btn">
                                <i class="ti ti-download"></i> Télécharger {{ basename($attachment) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Submissions card ── --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="ti ti-table"></i>
                Soumissions
                <span class="count-badge">{{ $tp->submissions->count() }}</span>
            </div>
        </div>

        @if($tp->submissions->count() > 0)
            <div class="table-wrap">
                <table class="submissions-table">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Date de soumission</th>
                            <th>Statut</th>
                            <th>Note</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tp->submissions as $submission)
                            <tr>
                                <td style="font-weight:600;color:var(--ink);">{{ $submission->student->name }}</td>
                                <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y à H:i') : '—' }}</td>
                                <td>
                                    @if($submission->grade !== null)
                                        <span class="badge badge-graded"><i class="ti ti-check"></i> Noté</span>
                                    @else
                                        <span class="badge badge-pending"><i class="ti ti-clock"></i> En attente</span>
                                    @endif
                                </td>
                                <td>
                                    @if($submission->grade !== null)
                                        <span class="grade-chip
                                            @if($submission->grade >= 14) grade-good
                                            @elseif($submission->grade >= 10) grade-average
                                            @else grade-poor
                                            @endif">
                                            {{ $submission->grade }}/20
                                        </span>
                                    @else
                                        <span style="color:var(--ink-4);">—</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('teacher.submissions.show', [$tp->id, $submission->id]) }}"
                                       class="btn-view">
                                        <i class="ti ti-eye"></i> Voir & Noter
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="ti ti-file-off"></i></div>
                <h3>Aucune soumission</h3>
                <p>Les étudiants n'ont pas encore soumis ce TP.</p>
            </div>
        @endif
    </div>

</div>
@endsection