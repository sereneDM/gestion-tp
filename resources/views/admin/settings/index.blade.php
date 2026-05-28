@extends('layouts.admin')

@section('title', 'Paramètres Système')

@section('breadcrumb')
    <span class="tb-bc-current">Paramètres</span>
@endsection

@section('topbar-actions')
    <button form="settings-form" type="submit" class="tb-btn tb-btn-primary">
        <i class="ti ti-device-floppy"></i> Enregistrer
    </button>
@endsection

@section('extra-styles')
<style>
    .settings-wrapper { max-width: 820px; margin: 0 auto; text-align: center; }
    .settings-wrapper .page-title, .settings-wrapper .page-subtitle { margin-left: auto; margin-right: auto; }

    .settings-section {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 18px;
        box-shadow: var(--shadow-sm);
    }

    .settings-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--line);
        background: var(--surface-2);
    }

    .settings-section-icon {
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        background: var(--accent-bg);
        display: flex; align-items: center; justify-content: center;
        color: var(--accent); font-size: 15px;
        flex-shrink: 0;
    }

    .settings-section-title {
        font-size: 13px; font-weight: 700; color: var(--ink);
    }
    .settings-section-desc {
        font-size: 11.5px; color: var(--ink-4); margin-top: 1px;
    }

    .settings-body { padding: 20px; display: flex; flex-direction: column; gap: 1.25rem; }

    .settings-row {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 1rem;
        align-items: start;
    }
    @media (max-width: 700px) { .settings-row { grid-template-columns: 1fr; } }

    .settings-row-label {
        font-size: 13px; font-weight: 600; color: var(--ink-2);
        padding-top: 0.55rem;
    }
    .settings-row-hint {
        font-size: 11.5px; color: var(--ink-4); margin-top: 2px;
    }

    .settings-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--line-2);
        border-radius: var(--radius-sm);
        font-size: 13px; font-family: inherit;
        background: var(--surface);
        color: var(--ink);
        transition: border-color .2s, box-shadow .2s;
    }
    .settings-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-bg);
    }
    textarea.settings-input { min-height: 80px; resize: vertical; line-height: 1.5; }

    /* Toggle switch */
    .toggle-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding-top: 4px;
    }
    .toggle-switch {
        position: relative;
        width: 40px; height: 22px;
        flex-shrink: 0;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background: var(--line-2);
        border-radius: 34px;
        transition: .3s;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px; width: 16px;
        left: 3px; bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: .3s;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    input:checked + .toggle-slider { background: var(--success); }
    input:checked + .toggle-slider:before { transform: translateX(18px); }
    .toggle-text { font-size: 13px; color: var(--ink-2); }

    /* Footer actions --  */
    .footer-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
        padding: 16px 20px;
        background: var(--surface-2);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
    }

    .btn-save {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px;
        background: var(--accent); color: white;
        border: none; border-radius: var(--radius-md);
        font-size: 13px; font-weight: 700; font-family: inherit;
        cursor: pointer; transition: background .2s;
        box-shadow: 0 2px 8px rgba(61,90,254,.25);
    }
    .btn-save:hover { background: var(--accent-2); }

    .btn-reset {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px;
        background: transparent; color: var(--danger);
        border: 1px solid rgba(229,57,53,.25);
        border-radius: var(--radius-md);
        font-size: 13px; font-weight: 700; font-family: inherit;
        cursor: pointer; transition: background .2s;
    }
    .btn-reset:hover { background: #fef2f2; border-color: var(--danger); }

    .info-banner {
        display: flex; align-items: flex-start; gap: 10px;
        background: var(--accent-bg);
        border: 1px solid rgba(61,90,254,.15);
        border-radius: var(--radius-md);
        padding: 11px 14px;
        margin-bottom: 20px;
        font-size: 12.5px; color: var(--accent); line-height: 1.5;
    }
</style>
@endsection

@section('content')
<div class="settings-wrapper">
    <h1 class="page-title">Paramètres système</h1>
    <p class="page-subtitle">Configurez le comportement global de la plateforme.</p>

    <div class="info-banner">
        <i class="ti ti-info-circle" style="font-size:16px; flex-shrink:0; margin-top:1px;"></i>
        Ces réglages affectent l'ensemble de la plateforme. Le nom et la description apparaissent sur la page de connexion.
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" id="settings-form">
        @csrf @method('PUT')

        {{-- General --}}
        <div class="settings-section">
            <div class="settings-section-header">
                <div class="settings-section-icon"><i class="ti ti-settings"></i></div>
                <div>
                    <div class="settings-section-title">Général</div>
                    <div class="settings-section-desc">Nom et identité visuelle de la plateforme</div>
                </div>
            </div>
            <div class="settings-body">
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">Nom de la plateforme</div>
                        <div class="settings-row-hint">Affiché sur la page de connexion et dans l'onglet navigateur</div>
                    </div>
                    <input type="text" name="site_name" class="settings-input"
                           value="{{ $settings['general']['site_name']['value'] }}"
                           placeholder="{{ \App\Models\Setting::get('site_name', 'Plateforme TP') }}">
                </div>
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">Description</div>
                        <div class="settings-row-hint">Sous-titre affiché sur la page de connexion</div>
                    </div>
                    <textarea name="site_description" class="settings-input"
                              placeholder="Plateforme pour la gestion des travaux pratiques">{{ $settings['general']['site_description']['value'] }}</textarea>
                </div>
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">Email de contact</div>
                        <div class="settings-row-hint">Adresse affichée pour le support</div>
                    </div>
                    <input type="email" name="contact_email" class="settings-input"
                           value="{{ $settings['general']['contact_email']['value'] }}"
                           placeholder="contact@example.com">
                </div>
            </div>
        </div>

        {{-- Academic --}}
        <div class="settings-section">
            <div class="settings-section-header">
                <div class="settings-section-icon" style="background:var(--success-bg, #ecfdf5); color:var(--success, #10b981);"><i class="ti ti-school"></i></div>
                <div>
                    <div class="settings-section-title">Académique</div>
                    <div class="settings-section-desc">Paramètres du semestre en cours</div>
                </div>
            </div>
            <div class="settings-body">
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">Semestre actuel</div>
                        <div class="settings-row-hint">Nom du semestre affiché aux utilisateurs</div>
                    </div>
                    <input type="text" name="semester_name" class="settings-input"
                           value="{{ $settings['academic']['semester_name']['value'] }}"
                           placeholder="Semestre 1 - 2025/2026">
                </div>
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">Date de début</div>
                        <div class="settings-row-hint">Début du semestre actuel</div>
                    </div>
                    <input type="date" name="semester_start_date" class="settings-input"
                           value="{{ $settings['academic']['semester_start_date']['value'] }}">
                </div>
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">Date de fin</div>
                        <div class="settings-row-hint">Fin du semestre actuel</div>
                    </div>
                    <input type="date" name="semester_end_date" class="settings-input"
                           value="{{ $settings['academic']['semester_end_date']['value'] }}">
                </div>
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">TPs max par étudiant</div>
                        <div class="settings-row-hint">Nombre maximum de TPs par étudiant par semestre</div>
                    </div>
                    <input type="number" name="max_tp_per_student" class="settings-input"
                           value="{{ $settings['academic']['max_tp_per_student']['value'] }}"
                           min="1" max="100" style="max-width:120px;">
                </div>
            </div>
        </div>

        {{-- Submissions --}}
        <div class="settings-section">
            <div class="settings-section-header">
                <div class="settings-section-icon" style="background:#fff3e0; color:#f57c00;"><i class="ti ti-file-upload"></i></div>
                <div>
                    <div class="settings-section-title">Soumissions</div>
                    <div class="settings-section-desc">Règles pour les fichiers déposés par les étudiants</div>
                </div>
            </div>
            <div class="settings-body">
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">Taille max (Mo)</div>
                        <div class="settings-row-hint">Taille maximale par fichier soumis</div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <input type="number" name="max_file_size" class="settings-input"
                               value="{{ $settings['submissions']['max_file_size']['value'] }}"
                               min="1" max="500" style="max-width:120px;">
                        <span style="font-size:13px; color:var(--ink-3);">Mo</span>
                    </div>
                </div>
                <div class="settings-row">
                    <div>
                        <div class="settings-row-label">Types autorisés</div>
                        <div class="settings-row-hint">Extensions séparées par des virgules (ex: pdf,zip,docx)</div>
                    </div>
                    <input type="text" name="allowed_file_types" class="settings-input"
                           value="{{ $settings['submissions']['allowed_file_types']['value'] }}"
                           placeholder="pdf,doc,docx,zip">
                </div>
            </div>
        </div>

    </form>

    {{-- Footer --}}
    <div class="footer-actions">
        <button form="settings-form" type="submit" class="btn-save">
            <i class="ti ti-device-floppy"></i> Enregistrer les paramètres
        </button>
        <form method="POST" action="{{ route('admin.settings.reset') }}" style="margin:0;"
              onsubmit="return confirm('Réinitialiser tous les paramètres aux valeurs par défaut ?')">
            @csrf
            <button type="submit" class="btn-reset">
                <i class="ti ti-refresh"></i> Réinitialiser
            </button>
        </form>
    </div>
</div>
@endsection