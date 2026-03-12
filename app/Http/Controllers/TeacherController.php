<?php

namespace App\Http\Controllers;

use App\Models\TP;
use App\Models\ClassModel;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// Add at the top with other use statements
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Models\User;           // ← ADD THIS LINE


class TeacherController extends Controller
{
    public function myCourses()
{
    $teacher = Auth::user();
    $courses = ClassModel::where('teacher_id', $teacher->id)
                         ->withCount('students')
                         ->orderBy('status')
                         ->orderBy('created_at', 'desc')
                         ->get();
    
    return view('teacher.courses.index', compact('courses'));
}

// Show create course form
public function createCourse()
{
    return view('teacher.courses.create');
}

// Store new course
public function storeCourse(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $course = ClassModel::create([
        'name' => $request->name,
        'description' => $request->description,
        'teacher_id' => Auth::id(),
        'status' => 'active',
    ]);

    return redirect()->route('teacher.courses.show', $course->id)
                     ->with('success', 'Cours créé avec succès! Partagez le code avec vos étudiants.');
}

// Show course details
public function showCourse($id)
{
    $course = ClassModel::where('teacher_id', Auth::id())
                        ->with('students')
                        ->findOrFail($id);
    
    return view('teacher.courses.show', compact('course'));
}

// Edit course
public function editCourse($id)
{
    $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($id);
    return view('teacher.courses.edit', compact('course'));
}

// Update course
public function updateCourse(Request $request, $id)
{
    $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:active,archived',
    ]);

    $course->update([
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->status,
    ]);

    return redirect()->route('teacher.courses.show', $course->id)
                     ->with('success', 'Cours mis à jour avec succès!');
}

// Regenerate join code
public function regenerateCode($id)
{
    $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($id);
    $newCode = $course->regenerateJoinCode();

    return redirect()->route('teacher.courses.show', $course->id)
                     ->with('success', 'Nouveau code généré: ' . $newCode);
}

// Remove student from course
public function removeStudent($courseId, $studentId)
{
    $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($courseId);
    $course->students()->detach($studentId);

    return redirect()->route('teacher.courses.show', $courseId)
                     ->with('success', 'Étudiant retiré du cours.');
}

// Delete course
public function deleteCourse($id)
{
    $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($id);
    $course->delete();

    return redirect()->route('teacher.courses.index')
                     ->with('success', 'Cours supprimé avec succès!');
}
    public function dashboard()
    {
        $teacher = Auth::user();
        $tpsCount = TP::where('teacher_id', $teacher->id)->count();
        $classesCount = ClassModel::where('teacher_id', $teacher->id)->count();
        $pendingSubmissions = Submission::whereHas('tp', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->where('status', 'submitted')->count();

        return view('teacher.dashboard', compact('tpsCount', 'classesCount', 'pendingSubmissions'));
    }

    // List all TPs
    public function indexTPs()
    {
        $tps = TP::where('teacher_id', Auth::id())
                  ->with('class')
                  ->orderBy('created_at', 'desc')
                  ->get();
        
        return view('teacher.tps.index', compact('tps'));
    }

    // Show form to create TP
    public function createTP()
    {
        $classes = ClassModel::where('teacher_id', Auth::id())->get();
        return view('teacher.tps.create', compact('classes'));
    }

    // Store new TP
    public function storeTP(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'nullable|exists:classes,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:draft,published',
        ]);

        TP::create([
            'title' => $request->title,
            'description' => $request->description,
            'teacher_id' => Auth::id(),
            'class_id' => $request->class_id,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('teacher.tps.index')
                         ->with('success', 'TP créé avec succès!');
    }

    // Show TP details and submissions
    public function showTP($id)
    {
        $tp = TP::with(['class', 'submissions.student'])
                ->where('teacher_id', Auth::id())
                ->findOrFail($id);
        
        return view('teacher.tps.show', compact('tp'));
    }

    // Show form to edit TP
    public function editTP($id)
    {
        $tp = TP::where('teacher_id', Auth::id())->findOrFail($id);
        $classes = ClassModel::where('teacher_id', Auth::id())->get();
        return view('teacher.tps.edit', compact('tp', 'classes'));
    }

    // Update TP
    public function updateTP(Request $request, $id)
    {
        $tp = TP::where('teacher_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'class_id' => 'nullable|exists:classes,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:draft,published,closed',
        ]);

        $tp->update([
            'title' => $request->title,
            'description' => $request->description,
            'class_id' => $request->class_id,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('teacher.tps.index')
                         ->with('success', 'TP modifié avec succès!');
    }

    // Delete TP
    public function destroyTP($id)
    {
        $tp = TP::where('teacher_id', Auth::id())->findOrFail($id);
        $tp->delete();

        return redirect()->route('teacher.tps.index')
                         ->with('success', 'TP supprimé avec succès!');
    }

    // Show submission for grading
    public function showSubmission($tpId, $submissionId)
    {
        $submission = Submission::with(['tp', 'student'])
                                ->whereHas('tp', function($query) {
                                    $query->where('teacher_id', Auth::id());
                                })
                                ->where('tp_id', $tpId)
                                ->findOrFail($submissionId);

        return view('teacher.submissions.show', compact('submission'));
    }

    // Grade submission
    public function gradeSubmission(Request $request, $tpId, $submissionId)
    {
        $submission = Submission::whereHas('tp', function($query) {
                                    $query->where('teacher_id', Auth::id());
                                })
                                ->where('tp_id', $tpId)
                                ->findOrFail($submissionId);

        $request->validate([
            'grade' => 'required|numeric|min:0|max:20',
            'teacher_comment' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $request->grade,
            'teacher_comment' => $request->teacher_comment,
            'status' => 'graded',
        ]);

        return redirect()->route('teacher.tps.show', $tpId)
                         ->with('success', 'Note enregistrée avec succès!');
    }

// Add these methods to the class

// Student progress tracking
public function studentProgress()
{
    $teacher = Auth::user();
    
    // Get all classes taught by this teacher
    $classes = ClassModel::where('teacher_id', $teacher->id)
                         ->with(['students'])
                         ->get();
    
    return view('teacher.progress.index', compact('classes'));
}

// View specific student's progress
public function showStudentProgress($studentId)
{
    $teacher = Auth::user();
    $student = User::where('role', 'student')->findOrFail($studentId);
    
    // Get all submissions for TPs created by this teacher
    $submissions = Submission::whereHas('tp', function($query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->where('student_id', $studentId)
                            ->with('tp')
                            ->orderBy('submitted_at', 'desc')
                            ->get();
    
    // Calculate statistics
    $totalSubmissions = $submissions->count();
    $gradedSubmissions = $submissions->where('status', 'graded')->count();
    $averageGrade = $submissions->where('status', 'graded')->avg('grade');
    
    // Get attendance records
    $attendances = Attendance::where('student_id', $studentId)
                             ->where('teacher_id', $teacher->id)
                             ->orderBy('date', 'desc')
                             ->get();
    
    $attendanceStats = [
        'total' => $attendances->count(),
        'present' => $attendances->where('status', 'present')->count(),
        'absent' => $attendances->where('status', 'absent')->count(),
        'late' => $attendances->where('status', 'late')->count(),
    ];
    
    return view('teacher.progress.show', compact('student', 'submissions', 'totalSubmissions', 'gradedSubmissions', 'averageGrade', 'attendances', 'attendanceStats'));
}

// Attendance management - List sessions
public function attendanceIndex()
{
    $teacher = Auth::user();
    $classes = ClassModel::where('teacher_id', $teacher->id)->get();
    
    return view('teacher.attendance.index', compact('classes'));
}

// Show attendance for specific class and date
public function attendanceShow(Request $request)
{
    $teacher = Auth::user();
    $classId = $request->class_id;
    $date = $request->date ?? now()->format('Y-m-d');
    
    $class = ClassModel::where('teacher_id', $teacher->id)
                       ->with('students')
                       ->findOrFail($classId);
    
    // Get existing attendance records for this date
    $existingAttendances = Attendance::where('class_id', $classId)
                                     ->where('date', $date)
                                     ->get()
                                     ->keyBy('student_id');
    
    return view('teacher.attendance.take', compact('class', 'date', 'existingAttendances'));
}

// Save attendance
public function attendanceSave(Request $request)
{
    $teacher = Auth::user();
    
    $request->validate([
        'class_id' => 'required|exists:classes,id',
        'date' => 'required|date',
        'attendance' => 'required|array',
    ]);
    
    foreach ($request->attendance as $studentId => $status) {
        Attendance::updateOrCreate(
            [
                'student_id' => $studentId,
                'class_id' => $request->class_id,
                'date' => $request->date,
            ],
            [
                'teacher_id' => $teacher->id,
                'status' => $status,
                'notes' => $request->notes[$studentId] ?? null,
            ]
        );
    }
    
    return redirect()->route('teacher.attendance.index')
                     ->with('success', 'Présence enregistrée avec succès!');
}

// Statistics dashboard
public function statistics()
{
    $teacher = Auth::user();
    
    // TP Statistics
    $totalTPs = TP::where('teacher_id', $teacher->id)->count();
    $publishedTPs = TP::where('teacher_id', $teacher->id)->where('status', 'published')->count();
    $draftTPs = TP::where('teacher_id', $teacher->id)->where('status', 'draft')->count();
    
    // Submission Statistics
    $totalSubmissions = Submission::whereHas('tp', function($query) use ($teacher) {
                                    $query->where('teacher_id', $teacher->id);
                                })->count();
    
    $gradedSubmissions = Submission::whereHas('tp', function($query) use ($teacher) {
                                    $query->where('teacher_id', $teacher->id);
                                })->where('status', 'graded')->count();
    
    $pendingSubmissions = Submission::whereHas('tp', function($query) use ($teacher) {
                                    $query->where('teacher_id', $teacher->id);
                                })->where('status', 'submitted')->count();
    
    // Average grade
    $averageGrade = Submission::whereHas('tp', function($query) use ($teacher) {
                                $query->where('teacher_id', $teacher->id);
                            })
                            ->where('status', 'graded')
                            ->avg('grade');
    
    // Grade distribution
    $gradeDistribution = Submission::whereHas('tp', function($query) use ($teacher) {
                                    $query->where('teacher_id', $teacher->id);
                                })
                                ->where('status', 'graded')
                                ->whereNotNull('grade')
                                ->select(
                                    DB::raw('CASE 
                                        WHEN grade >= 16 THEN "16-20"
                                        WHEN grade >= 14 THEN "14-16"
                                        WHEN grade >= 12 THEN "12-14"
                                        WHEN grade >= 10 THEN "10-12"
                                        ELSE "0-10"
                                    END as grade_range'),
                                    DB::raw('COUNT(*) as count')
                                )
                                ->groupBy('grade_range')
                                ->get()
                                ->pluck('count', 'grade_range');
    
    // Students by class
    $classes = ClassModel::where('teacher_id', $teacher->id)
                         ->withCount('students')
                         ->get();
    
    // Recent submissions
    $recentSubmissions = Submission::whereHas('tp', function($query) use ($teacher) {
                                    $query->where('teacher_id', $teacher->id);
                                })
                                ->with(['student', 'tp'])
                                ->orderBy('submitted_at', 'desc')
                                ->limit(10)
                                ->get();
    
    // Attendance statistics
    $attendanceStats = Attendance::where('teacher_id', $teacher->id)
                                 ->select('status', DB::raw('COUNT(*) as count'))
                                 ->groupBy('status')
                                 ->get()
                                 ->pluck('count', 'status');
    
    return view('teacher.statistics.index', compact(
        'totalTPs',
        'publishedTPs',
        'draftTPs',
        'totalSubmissions',
        'gradedSubmissions',
        'pendingSubmissions',
        'averageGrade',
        'gradeDistribution',
        'classes',
        'recentSubmissions',
        'attendanceStats'
    ));
}
}