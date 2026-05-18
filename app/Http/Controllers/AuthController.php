<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email:rfc',
            'password' => 'required',
            'role'     => 'required|in:etudiant,enseignant,admin',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.']);
        }

        $user = Auth::user();

        // Map the submitted role value to the User model's role-check methods.
        $roleMatches = match($request->role) {
            'admin'      => $user->isAdmin(),
            'enseignant' => $user->isTeacher(),
            'etudiant'   => ! $user->isAdmin() && ! $user->isTeacher(),
        };

        if (! $roleMatches) {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Ce compte n\'existe pas pour ce type de profil.']);
        }

        // Check if user must reset password
        if ($user->must_reset_password) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Vous devez configurer votre mot de passe. Veuillez vérifier votre email pour le lien de configuration.']);
        }

        $request->session()->regenerate();

        // Log the activity
        activity()
            ->causedBy($user)
            ->log('Connexion réussie');

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->isTeacher()) {
            return redirect()->intended(route('feed.index'));
        } else {
            return redirect()->intended(route('student.dashboard'));
        }
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Show password setup form (for new users)
    public function showPasswordSetup(Request $request, $token)
    {
        $email = $request->email;

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Ce lien de configuration est invalide.']);
        }

        if (now()->diffInHours($passwordReset->created_at) > 24) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Ce lien de configuration a expiré.']);
        }

        return view('auth.setup-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Setup new password (for new users)
    public function setupPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email:rfc|exists:users,email',
            'token'    => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ], [
            'password.regex' => 'Le mot de passe doit contenir au moins: 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.',
        ]);

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Ce lien est invalide.']);
        }

        if (now()->diffInHours($passwordReset->created_at) > 24) {
            return back()->withErrors(['email' => 'Ce lien a expiré.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->must_reset_password = false;
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Auth::login($user);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Mot de passe configuré avec succès! Bienvenue sur la plateforme.');
        } elseif ($user->isTeacher()) {
            return redirect()->route('feed.index')
                ->with('success', 'Mot de passe configuré avec succès! Bienvenue sur la plateforme.');
        } else {
            return redirect()->route('student.dashboard')
                ->with('success', 'Mot de passe configuré avec succès! Bienvenue sur la plateforme.');
        }
    }

    // Show forgot password form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Send password reset link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc|exists:users,email',
        ], [
            'email.exists' => 'Aucun compte associé à cet email.',
        ]);

        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetLink = route('password.reset', ['token' => $token, 'email' => $request->email]);

        Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

        return back()->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
    }

    // Show reset password form
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email:rfc|exists:users,email',
            'token'    => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                function ($attribute, $value, $fail) use ($request) {
                    $user = User::where('email', $request->email)->first();
                    if ($user && Hash::check($value, $user->password)) {
                        $fail('Le nouveau mot de passe doit être différent de l\'ancien.');
                    }
                },
            ],
        ]);

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Ce lien de réinitialisation est invalide.']);
        }

        if (now()->diffInHours($passwordReset->created_at) > 24) {
            return back()->withErrors(['email' => 'Ce lien de réinitialisation a expiré.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Mot de passe réinitialisé avec succès! Vous pouvez maintenant vous connecter.');
    }
}