<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\TP;
use App\Models\Submission;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Show admin dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Show list of all users (students and teachers only)
    public function index()
    {
        // Get all users, ordered by role then name
        $users = User::orderBy('role')
                     ->orderBy('name')
                     ->get();
        
        return view('admin.users.index', compact('users'));
    }

    // Show form to create a new user
    public function create()
    {
        return view('admin.users.create');
    }

    // Store a new user in database
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:student,teacher',
        ]);

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur créé avec succès!');
    }

    // Show form to edit an existing user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent editing admin accounts
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                           ->with('error', 'Vous ne pouvez pas modifier un administrateur!');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    // Update an existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:student,teacher',
        ]);

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur modifié avec succès!');
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting admin accounts
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                           ->with('error', 'Vous ne pouvez pas supprimer un administrateur!');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Utilisateur supprimé avec succès!');
    }

    // Quick role update
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'role' => 'required|in:student,teacher,admin',
        ]);
        
        $oldRole = $user->role;
        $user->role = $request->role;
        $user->save();
        
        return redirect()->route('admin.users.index')
                         ->with('success', "Rôle changé de {$oldRole} à {$request->role} avec succès!");
    }

    // Statistics dashboard
    public function statistics()
    {
        // Users statistics
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        
        // Classes statistics
        $totalClasses = ClassModel::count();
        $classesWithStudents = ClassModel::has('students')->count();
        $classesWithTeachers = ClassModel::whereNotNull('teacher_id')->count();
        
        // TPs statistics
        $totalTPs = TP::count();
        $publishedTPs = TP::where('status', 'published')->count();
        $draftTPs = TP::where('status', 'draft')->count();
        
        // Submissions statistics
        $totalSubmissions = Submission::count();
        $gradedSubmissions = Submission::where('status', 'graded')->count();
        $pendingSubmissions = Submission::where('status', 'submitted')->count();
        $averageGrade = Submission::where('status', 'graded')->avg('grade');
        
        // Attendance statistics
        $totalAttendances = Attendance::count();
        $presentCount = Attendance::where('status', 'present')->count();
        $absentCount = Attendance::where('status', 'absent')->count();
        $lateCount = Attendance::where('status', 'late')->count();
        
        // Recent activity
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $recentSubmissions = Submission::with(['student', 'tp'])->orderBy('submitted_at', 'desc')->limit(10)->get();
        $recentClasses = ClassModel::orderBy('created_at', 'desc')->limit(5)->get();
        
        // Users by creation date (last 6 months)
        $usersByMonth = User::where('created_at', '>=', now()->subMonths(6))
                            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();
        
        // Top performing students
        $topStudents = Submission::where('status', 'graded')
                                 ->selectRaw('student_id, AVG(grade) as avg_grade, COUNT(*) as total_submissions')
                                 ->groupBy('student_id')
                                 ->having('total_submissions', '>=', 3)
                                 ->orderByDesc('avg_grade')
                                 ->limit(10)
                                 ->get()
                                 ->map(function($item) {
                                     $item->student = User::find($item->student_id);
                                     return $item;
                                 });
        
        // Most active teachers
        $activeTeachers = TP::selectRaw('teacher_id, COUNT(*) as tps_count')
                            ->groupBy('teacher_id')
                            ->orderByDesc('tps_count')
                            ->limit(10)
                            ->get()
                            ->map(function($item) {
                                $item->teacher = User::find($item->teacher_id);
                                return $item;
                            });
        
        return view('admin.statistics', compact(
            'totalUsers', 'totalStudents', 'totalTeachers', 'totalAdmins',
            'totalClasses', 'classesWithStudents', 'classesWithTeachers',
            'totalTPs', 'publishedTPs', 'draftTPs',
            'totalSubmissions', 'gradedSubmissions', 'pendingSubmissions', 'averageGrade',
            'totalAttendances', 'presentCount', 'absentCount', 'lateCount',
            'recentUsers', 'recentSubmissions', 'recentClasses',
            'usersByMonth', 'topStudents', 'activeTeachers'
        ));
    }

    // System logs and monitoring
    public function systemLogs()
    {
        // Get recent activities (you can expand this with a proper activity log table)
        $activities = [
            [
                'action' => 'Connexion',
                'user' => 'admin@example.com',
                'timestamp' => now()->subMinutes(5),
                'details' => 'Connexion réussie',
            ],
            [
                'action' => 'Création TP',
                'user' => 'teacher@example.com',
                'timestamp' => now()->subHour(),
                'details' => 'TP "Introduction à Laravel" créé',
            ],
            [
                'action' => 'Soumission',
                'user' => 'student@example.com',
                'timestamp' => now()->subHours(2),
                'details' => 'TP soumis pour correction',
            ],
        ];
        
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database' => config('database.default'),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug') ? 'Activé' : 'Désactivé',
            'timezone' => config('app.timezone'),
        ];
        
        return view('admin.system-logs', compact('activities', 'systemInfo'));
    }
}