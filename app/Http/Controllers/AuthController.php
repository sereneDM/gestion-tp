<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle login logic
    public function login(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            $user = Auth::user();

            // Redirect based on role
            switch ($user->role) {
                case 'student':
                    return redirect()->route('student.dashboard');
                case 'teacher':
                    return redirect()->route('teacher.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                default:
                    return redirect()->route('login');
            }
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}