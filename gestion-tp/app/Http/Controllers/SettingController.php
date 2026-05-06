<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Show settings page
    public function index()
    {
        // Define default settings structure
        $defaultSettings = [
            'general' => [
                'site_name' => [
                    'label' => 'Nom de la plateforme',
                    'type' => 'text',
                    'default' => 'Plateforme de Gestion des TP',
                ],
                'site_description' => [
                    'label' => 'Description de la plateforme',
                    'type' => 'textarea',
                    'default' => 'Plateforme pour la gestion des travaux pratiques',
                ],
                'contact_email' => [
                    'label' => 'Email de contact',
                    'type' => 'email',
                    'default' => 'contact@example.com',
                ],
            ],
            'academic' => [
                'semester_name' => [
                    'label' => 'Nom du semestre actuel',
                    'type' => 'text',
                    'default' => 'Semestre 1 - 2025/2026',
                ],
                'semester_start_date' => [
                    'label' => 'Date de début du semestre',
                    'type' => 'date',
                    'default' => date('Y-m-d'),
                ],
                'semester_end_date' => [
                    'label' => 'Date de fin du semestre',
                    'type' => 'date',
                    'default' => date('Y-m-d', strtotime('+6 months')),
                ],
                'max_tp_per_student' => [
                    'label' => 'Nombre maximum de TP par étudiant',
                    'type' => 'number',
                    'default' => '10',
                ],
            ],
            'submissions' => [
                'max_file_size' => [
                    'label' => 'Taille maximale des fichiers (Mo)',
                    'type' => 'number',
                    'default' => '10',
                ],
                'allowed_file_types' => [
                    'label' => 'Types de fichiers autorisés',
                    'type' => 'text',
                    'default' => 'pdf,doc,docx,zip',
                ],
            ],
        ];

        // Get current values from database
        $settings = [];
        foreach ($defaultSettings as $category => $categorySettings) {
            foreach ($categorySettings as $key => $config) {
                $settings[$category][$key] = [
                    'label' => $config['label'],
                    'type' => $config['type'],
                    'value' => Setting::get($key, $config['default']),
                ];
            }
        }

        return view('admin.settings.index', compact('settings'));
    }

    // Update settings
    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            // Handle checkboxes (they don't send value if unchecked)
            if (!isset($value)) {
                $value = '0';
            }

            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
                         ->with('success', 'Paramètres mis à jour avec succès!');
    }

    // Reset all settings to default
    public function reset()
    {
        Setting::truncate();
        
        return redirect()->route('admin.settings.index')
                         ->with('success', 'Paramètres réinitialisés aux valeurs par défaut!');
    }
}