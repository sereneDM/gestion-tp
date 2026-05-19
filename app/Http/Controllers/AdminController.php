<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCreated;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\TP;
use App\Models\Submission;
use App\Models\Attendance;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Show admin dashboard
    public function dashboard()
    {
        $totalUsers    = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses  = ClassModel::where('status', 'active')->count();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalStudents', 'totalTeachers', 'totalClasses'
        ));
    }

    // Show list of all users — hides the currently logged-in admin
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
                     ->orderBy('role')
                     ->orderBy('name')
                     ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:users,email',
            'role'  => 'required|in:student,teacher,admin',
        ]);

        $temporaryPassword = Str::random(12);
        $token = Str::random(60);

        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'password'           => Hash::make($temporaryPassword),
            'role'               => $request->role,
            'must_reset_password'=> true,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log("Utilisateur créé: {$user->email} ({$user->role})");

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        try {
            Mail::to($user->email)->send(
                new AccountCreated($user->name, $user->email, $temporaryPassword, $user->role, $token)
            );
            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur créé avec succès! Email envoyé à ' . $user->email);
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur créé. Email non envoyé: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:users,email,' . $id,
            'role'  => 'required|in:student,teacher,admin',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte!');
        }

        activity()
            ->causedBy(Auth::user())
            ->log("Utilisateur supprimé: {$user->email}");

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès!');
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas modifier votre propre rôle!');
        }

        $request->validate(['role' => 'required|in:student,teacher,admin']);

        $oldRole    = $user->role;
        $user->role = $request->role;
        $user->save();

        activity()
            ->causedBy(Auth::user())
            ->log("Rôle changé: {$user->email} — {$oldRole} → {$request->role}");

        return redirect()->route('admin.users.index')
            ->with('success', "Rôle changé de {$oldRole} à {$request->role}!");
    }

    // Statistics — fixed counts, no recent submissions
    public function statistics()
    {
        $totalUsers    = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalAdmins   = User::where('role', 'admin')->count();

        $totalClasses        = ClassModel::count();
        $classesWithStudents = ClassModel::has('students')->count();
        $classesWithTeachers = ClassModel::whereNotNull('teacher_id')->count();

        $totalTPs    = TP::count();
        $publishedTPs= TP::where('status', 'published')->count();
        $draftTPs    = TP::where('status', 'draft')->count();

        $totalSubmissions   = Submission::count();
        $gradedSubmissions  = Submission::where('status', 'graded')->count();
        $pendingSubmissions = Submission::where('status', 'submitted')->count();
        $averageGrade       = Submission::where('status', 'graded')->avg('grade');

        $totalAttendances = Attendance::count();
        $presentCount     = Attendance::where('status', 'present')->count();
        $absentCount      = Attendance::where('status', 'absent')->count();
        $lateCount        = Attendance::where('status', 'late')->count();

        // Top performing students (need at least 1 graded submission)
        $topStudents = Submission::where('status', 'graded')
            ->selectRaw('student_id, AVG(grade) as avg_grade, COUNT(*) as total_submissions')
            ->groupBy('student_id')
            ->having('total_submissions', '>=', 1)
            ->orderByDesc('avg_grade')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->student = User::find($item->student_id);
                return $item;
            })
            ->filter(fn($item) => $item->student !== null);

        // Most active teachers
        $activeTeachers = TP::selectRaw('teacher_id, COUNT(*) as tps_count')
            ->groupBy('teacher_id')
            ->orderByDesc('tps_count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->teacher = User::find($item->teacher_id);
                return $item;
            })
            ->filter(fn($item) => $item->teacher !== null);

        return view('admin.statistics', compact(
            'totalUsers', 'totalStudents', 'totalTeachers', 'totalAdmins',
            'totalClasses', 'classesWithStudents', 'classesWithTeachers',
            'totalTPs', 'publishedTPs', 'draftTPs',
            'totalSubmissions', 'gradedSubmissions', 'pendingSubmissions', 'averageGrade',
            'totalAttendances', 'presentCount', 'absentCount', 'lateCount',
            'topStudents', 'activeTeachers'
        ));
    }

    // System logs with filters
    public function systemLogs(Request $request)
    {
        $query = \Spatie\Activitylog\Models\Activity::with('causer')->latest();

        // Filter by causer (user search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('causer', fn($q) => $q->where('name', 'like', "%{$search}%")
                                                     ->orWhere('email', 'like', "%{$search}%"))
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->paginate(25)->withQueryString();

        return view('admin.system-logs', compact('activities'));
    }
}