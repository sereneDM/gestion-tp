<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\EmailChangeVerification;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'            => 'required|string|min:2|max:20',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $file     = $request->file('profile_picture');
            $path     = $file->store('profile_pictures', 'public');
            $fullPath = storage_path('app/public/' . $path);

            $manager = ImageManager::usingDriver(GdDriver::class);
            $image   = $manager->decodePath($fullPath);

            if ($request->filled(['crop_width', 'crop_height', 'crop_x', 'crop_y'])) {
                $image->crop(
                    (int) $request->crop_width,
                    (int) $request->crop_height,
                    (int) $request->crop_x,
                    (int) $request->crop_y
                );
            }

            $image->save($fullPath);
            $user->profile_picture = $path;
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès');
    }

    // Step 1: user requests email change → send code to NEW email
    public function requestEmailChange(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
        ], [
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre compte.',
        ]);

        if ($request->email === $user->email) {
            return back()->withErrors(['email' => 'Cette adresse email est déjà votre email actuel.']);
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->pending_email                = $request->email;
        $user->email_change_code            = Hash::make($code);
        $user->email_change_code_expires_at = now()->addMinutes(15);
        $user->save();

        Mail::to($request->email)->send(
            new EmailChangeVerification($user->name, $request->email, $code)
        );

        return back()->with('email_code_sent', true)
                     ->with('info', 'Un code de confirmation a été envoyé à ' . $request->email);
    }

    // Step 2: user submits the code → apply email change
    public function confirmEmailChange(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email_code' => 'required|string|size:6',
        ]);

        if (!$user->pending_email || !$user->email_change_code) {
            return back()->withErrors(['email_code' => 'Aucune demande de changement d\'email en cours.']);
        }

        if (now()->isAfter($user->email_change_code_expires_at)) {
            $user->pending_email                = null;
            $user->email_change_code            = null;
            $user->email_change_code_expires_at = null;
            $user->save();
            return back()->withErrors(['email_code' => 'Ce code a expiré. Veuillez recommencer.']);
        }

        if (!Hash::check($request->email_code, $user->email_change_code)) {
            return back()->withErrors(['email_code' => 'Code incorrect. Veuillez réessayer.'])
                         ->with('email_code_sent', true);
        }

        // Apply the email change
        $user->email                        = $user->pending_email;
        $user->pending_email                = null;
        $user->email_change_code            = null;
        $user->email_change_code_expires_at = null;
        $user->save();

        return back()->with('success', 'Adresse email mise à jour avec succès!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password'     => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ], [
            'new_password.min'   => 'Le mot de passe doit contenir au moins 8 caractères.',
            'new_password.regex' => 'Le mot de passe doit contenir au moins: 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&).',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.edit')
                         ->with('success', 'Mot de passe changé avec succès!');
    }

    public function deletePicture()
    {
        $user = Auth::user();

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->profile_picture = null;
        $user->save();

        return redirect()->route('profile.edit')
                         ->with('success', 'Photo de profil supprimée.');
    }
}