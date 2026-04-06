<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user must reset password
            if ($user->must_reset_password) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Vous devez configurer votre mot de passe. Veuillez vérifier votre email pour le lien de configuration.']);
            }

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isTeacher()) {
                return redirect()->intended(route('teacher.dashboard'));
            } else {
                return redirect()->intended(route('student.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
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
        
        // Verify token exists
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Ce lien de configuration est invalide.']);
        }

        // Check if token expired (24 hours)
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
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
        ], [
            'password.regex' => 'Le mot de passe doit contenir au moins: 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.',
        ]);

        // Verify token
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Ce lien est invalide.']);
        }

        // Check expiration
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            return back()->withErrors(['email' => 'Ce lien a expiré.']);
        }

        // Update user password and remove reset flag
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->must_reset_password = false;
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Log the user in automatically
        Auth::login($user);

        // Redirect to appropriate dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Mot de passe configuré avec succès! Bienvenue sur la plateforme.');
        } elseif ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard')
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
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate token
        $token = Str::random(60);

        // Delete old tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Create new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // In a real application, you would send an email here
        // For now, we'll just show the reset link on the page
        $resetLink = route('password.reset', ['token' => $token, 'email' => $request->email]);

        return back()->with('reset_link', $resetLink);
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
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        // Check if token exists and is valid
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Ce lien de réinitialisation est invalide.']);
        }

        // Check if token is expired (24 hours)
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            return back()->withErrors(['email' => 'Ce lien de réinitialisation a expiré.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
                         ->with('success', 'Mot de passe réinitialisé avec succès! Vous pouvez maintenant vous connecter.');
    }
}