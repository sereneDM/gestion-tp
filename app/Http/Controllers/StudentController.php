<?php

namespace App\Http\Controllers;

use App\Models\TP;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\ClassModel;


class StudentController extends Controller
{
    // Show join course form
public function showJoinCourse()
{
    return view('student.join-course');
}

// Join a course with code
public function joinCourse(Request $request)
{
    $request->validate([
        'join_code' => 'required|string',
    ]);

    $student = Auth::user();
    $joinCode = strtoupper(trim($request->join_code));

    // Find course by join code
    $course = ClassModel::where('join_code', $joinCode)
                        ->where('status', 'active')
                        ->first();

    if (!$course) {
        return back()->withErrors(['join_code' => 'Code invalide ou cours non disponible.']);
    }

    // Check if already enrolled
    if ($course->students()->where('student_id', $student->id)->exists()) {
        return back()->withErrors(['join_code' => 'Vous êtes déjà inscrit à ce cours.']);
    }

    // Enroll student
    $course->students()->attach($student->id);

    return redirect()->route('student.my-courses')
                     ->with('success', 'Vous avez rejoint le cours: ' . $course->name);
}

// Show student's enrolled courses
public function myCourses()
{
    $student = Auth::user();
    $courses = $student->classes()
                       ->with('teacher')
                       ->withCount('students')
                       ->get();
    
    return view('student.my-courses', compact('courses'));
}

// Leave a course
public function leaveCourse($courseId)
{
    $student = Auth::user();
    $course = ClassModel::findOrFail($courseId);
    
    // Check if student is enrolled
    if (!$course->students()->where('student_id', $student->id)->exists()) {
        return back()->withErrors(['error' => 'Vous n\'êtes pas inscrit à ce cours.']);
    }

    // Remove student from course
    $course->students()->detach($student->id);

    return redirect()->route('student.my-courses')
                     ->with('success', 'Vous avez quitté le cours: ' . $course->name);
}
    public function dashboard()
    {
        $student = Auth::user();
        
        // Get available TPs (published, and either no class assigned or student is in that class)
        $availableTPs = TP::where('status', 'published')
                          ->where(function($query) use ($student) {
                              $query->whereNull('class_id')
                                    ->orWhereHas('class.students', function($q) use ($student) {
                                        $q->where('users.id', $student->id);
                                    });
                          })
                          ->count();
        
        $submittedCount = Submission::where('student_id', $student->id)->count();
        $gradedCount = Submission::where('student_id', $student->id)
                                  ->where('status', 'graded')
                                  ->count();

        return view('student.dashboard', compact('availableTPs', 'submittedCount', 'gradedCount'));
    }

    // List all available TPs
    public function indexTPs()
    {
        $student = Auth::user();
        
        $tps = TP::where('status', 'published')
                 ->where(function($query) use ($student) {
                     $query->whereNull('class_id')
                           ->orWhereHas('class.students', function($q) use ($student) {
                               $q->where('users.id', $student->id);
                           });
                 })
                 ->with(['teacher', 'class'])
                 ->orderBy('due_date', 'asc')
                 ->get();
        
        // Check which TPs student has already submitted
        $submittedTpIds = Submission::where('student_id', $student->id)
                                    ->pluck('tp_id')
                                    ->toArray();

        return view('student.tps.index', compact('tps', 'submittedTpIds'));
    }

    // Show TP details
    public function showTP($id)
    {
        $student = Auth::user();
        
        $tp = TP::where('status', 'published')
                ->where(function($query) use ($student) {
                    $query->whereNull('class_id')
                          ->orWhereHas('class.students', function($q) use ($student) {
                              $q->where('users.id', $student->id);
                          });
                })
                ->with(['teacher', 'class'])
                ->findOrFail($id);
        
        // Check if student already submitted
        $submission = Submission::where('tp_id', $id)
                                ->where('student_id', $student->id)
                                ->first();

        return view('student.tps.show', compact('tp', 'submission'));
    }

    // Submit TP work
    public function submitTP(Request $request, $id)
    {
        $student = Auth::user();
        
        // Verify TP exists and is available
        $tp = TP::where('status', 'published')
                ->where(function($query) use ($student) {
                    $query->whereNull('class_id')
                          ->orWhereHas('class.students', function($q) use ($student) {
                              $q->where('users.id', $student->id);
                          });
                })
                ->findOrFail($id);

        // Check if already submitted
        $existingSubmission = Submission::where('tp_id', $id)
                                        ->where('student_id', $student->id)
                                        ->first();

        if ($existingSubmission) {
            return redirect()->route('student.tps.show', $id)
                           ->with('error', 'Vous avez déjà soumis ce TP!');
        }

        $request->validate([
            'content' => 'required|string|min:10',
        ]);

        // Determine if submission is late
        $status = 'submitted';
        if ($tp->due_date && now()->isAfter($tp->due_date)) {
            $status = 'late';
        }

        Submission::create([
            'tp_id' => $id,
            'student_id' => $student->id,
            'content' => $request->content,
            'status' => $status,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.tps.show', $id)
                       ->with('success', 'Votre travail a été soumis avec succès!');
    }

    // View my submissions
    public function mySubmissions()
    {
        $student = Auth::user();
        
        $submissions = Submission::where('student_id', $student->id)
                                 ->with(['tp.teacher'])
                                 ->orderBy('submitted_at', 'desc')
                                 ->get();

        return view('student.submissions.index', compact('submissions'));
    }

    // View specific submission details
    public function showSubmission($id)
    {
        $submission = Submission::where('student_id', Auth::id())
                                ->with(['tp', 'student'])
                                ->findOrFail($id);

        return view('student.submissions.show', compact('submission'));
    }
    // Add this method to StudentController

public function myProgress()
{
    $student = Auth::user();
    
    // Get all submissions
    $submissions = Submission::where('student_id', $student->id)
                             ->with('tp.teacher')
                             ->orderBy('submitted_at', 'desc')
                             ->get();
    
    // Calculate statistics
    $totalSubmissions = $submissions->count();
    $gradedSubmissions = $submissions->where('status', 'graded')->count();
    $pendingSubmissions = $submissions->where('status', 'submitted')->count();
    $averageGrade = $submissions->where('status', 'graded')->avg('grade');
    
    // Grade distribution
    $gradesByTP = $submissions->where('status', 'graded')
                              ->groupBy('tp_id')
                              ->map(function($group) {
                                  return [
                                      'tp' => $group->first()->tp,
                                      'grade' => $group->first()->grade,
                                      'submitted_at' => $group->first()->submitted_at,
                                  ];
                              })
                              ->sortByDesc('submitted_at');
    
    // Attendance records
    $attendances = Attendance::where('student_id', $student->id)
                             ->with('class')
                             ->orderBy('date', 'desc')
                             ->get();
    
    $attendanceStats = [
        'total' => $attendances->count(),
        'present' => $attendances->where('status', 'present')->count(),
        'absent' => $attendances->where('status', 'absent')->count(),
        'late' => $attendances->where('status', 'late')->count(),
        'excused' => $attendances->where('status', 'excused')->count(),
    ];
    
    return view('student.progress', compact(
        'totalSubmissions',
        'gradedSubmissions',
        'pendingSubmissions',
        'averageGrade',
        'gradesByTP',
        'attendances',
        'attendanceStats'
    ));
}
}