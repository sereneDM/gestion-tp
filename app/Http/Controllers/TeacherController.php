<?php

namespace App\Http\Controllers;

use App\Models\TP;
use App\Models\ClassModel;
use App\Models\Submission;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\Post;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function createCourse()
    {
        return view('teacher.courses.create');
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course = ClassModel::create([
            'name'        => $request->name,
            'description' => $request->description,
            'teacher_id'  => Auth::id(),
            'status'      => 'active',
        ]);

        return redirect()->route('teacher.courses.show', $course->id)
                         ->with('success', 'Cours créé avec succès! Partagez le code avec vos étudiants.');
    }

    public function showCourse($id)
    {
        $course = ClassModel::where('teacher_id', Auth::id())
                            ->with('students')
                            ->findOrFail($id);

        return view('teacher.courses.show', compact('course'));
    }

    public function editCourse($id)
    {
        $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($id);
        return view('teacher.courses.edit', compact('course'));
    }

    public function updateCourse(Request $request, $id)
    {
        $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,archived',
        ]);

        $course->update([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return redirect()->route('teacher.courses.show', $course->id)
                         ->with('success', 'Cours mis à jour avec succès!');
    }

    public function regenerateCode($id)
    {
        $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($id);
        $newCode = $course->regenerateJoinCode();

        return redirect()->route('teacher.courses.show', $course->id)
                         ->with('success', 'Nouveau code généré: ' . $newCode);
    }

    public function removeStudent($courseId, $studentId)
    {
        $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($courseId);
        $course->students()->detach($studentId);

        return redirect()->route('teacher.courses.show', $courseId)
                         ->with('success', 'Étudiant retiré du cours.');
    }

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

    public function createTP()
    {
        $classes = ClassModel::where('teacher_id', Auth::id())->get();
        return view('teacher.tps.create', compact('classes'));
    }

    public function storeTP(Request $request)
    {
       $request->validate([
    'title'      => 'required|string|max:255',
    'description' => 'nullable|string',
    'class_id'   => 'nullable|exists:classes,id',
    'due_date'   => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:now',
    'status'     => 'required|in:draft,published,closed',
    'attachment' => 'nullable|file|mimes:pdf|max:10240',
]);

if (!$request->filled('description') && !$request->hasFile('attachment')) {
    return back()->withErrors([
        'description' => 'Veuillez fournir une description ou un fichier PDF.'
    ])->withInput();
}

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('tp_attachments', $filename, 'public');
        }

        TP::create([
            'title'       => $request->title,
            'description' => $request->description,
            'teacher_id'  => Auth::id(),
            'class_id'    => $request->class_id,
            'due_date'    => $request->due_date ? Carbon::parse($request->due_date) : null,
            'status'      => $request->status ?? 'published',
            'attachments' => $attachmentPath,
        ]);

        return redirect()->route('teacher.tps.index')
                         ->with('success', 'TP créé avec succès!');
    }

    public function createTPForCourse($courseId)
    {
        $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($courseId);
        return view('teacher.courses.tps.create', compact('course'));
    }

    public function storeTPForCourse(Request $request, $courseId)
    {
        $course = ClassModel::where('teacher_id', Auth::id())->findOrFail($courseId);
$request->validate([
    'title'      => 'required|string|max:255',
    'description' => 'nullable|string',
    'due_date'   => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:now',
    'status'     => 'required|in:draft,published,closed',
    'attachment' => 'nullable|file|mimes:pdf|max:10240',
]);

if (!$request->filled('description') && !$request->hasFile('attachment')) {
    return back()->withErrors([
        'description' => 'Veuillez fournir une description ou un fichier PDF.'
    ])->withInput();
}

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('tp_attachments', $filename, 'public');
        }

        $dueDate = $request->due_date ? Carbon::parse($request->due_date) : null;

        $tp = TP::create([
            'title'       => $request->title,
            'description' => $request->description,
            'teacher_id'  => Auth::id(),
            'class_id'    => $courseId,
            'due_date'    => $dueDate,
            'status'      => $request->status,
            'attachments' => $attachmentPath,
        ]);

        if ($request->status === 'published') {
            $this->notifyStudentsNewTP($tp, $course);

            if ($request->has('create_post')) {
                Post::create([
                    'user_id'  => Auth::id(),
                    'class_id' => $courseId,
                    'tp_id'    => $tp->id,
                    'type'     => 'tp_posted',
                    'title'    => '📝 Nouveau TP: ' . $tp->title,
                    'content'  => $tp->description . "\n\nÉchéance: " .
                                  ($dueDate ? $dueDate->format('d/m/Y à H:i') : 'Non définie'),
                ]);
            }
        }

        return redirect(route('teacher.courses.show', $courseId) . '#tps')
                 ->with('success', 'TP créé avec succès!');
    }

    private function notifyStudentsNewTP($tp, $course)
    {
        foreach ($course->students as $student) {
            if (NotificationSetting::shouldNotify($student->id, $course->id, 'new_tp')) {
                Notification::createFor(
                    $student->id,
                    'new_tp',
                    '📝 Nouveau TP: ' . $tp->title,
                    'Un nouveau TP a été publié dans le cours ' . $course->name,
                    route('student.tps.show', $tp->id),
                    $tp->id
                );
            }
        }
    }

    public function showTP($id)
    {
        $tp = TP::with(['class', 'teacher', 'submissions.student'])->findOrFail($id);

        if ($tp->class->teacher_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('teacher.tps.show', compact('tp'));
    }

    public function editTP($id)
    {
        $tp = TP::with('class')->findOrFail($id);

        if ($tp->class->teacher_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('teacher.tps.edit', compact('tp'));
    }

    public function updateTP(Request $request, $id)
    {
        $tp = TP::with('class')->findOrFail($id);

        if ($tp->class->teacher_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
    'title'      => 'required|string|max:255',
    'description' => 'nullable|string',
    'due_date'   => 'nullable|date',
    'status'     => 'required|in:draft,published,closed',
    'attachment' => 'nullable|file|mimes:pdf|max:10240',
]);

if (!$request->filled('description') && !$request->hasFile('attachment') && !$tp->attachments) {
    return back()->withErrors([
        'description' => 'Veuillez fournir une description ou un fichier PDF.'
    ])->withInput();
}

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $tp->attachments = $file->storeAs('tp_attachments', $filename, 'public');
        }

        $tp->title       = $request->title;
        $tp->description = $request->description;
        $tp->due_date    = $request->due_date ? Carbon::parse($request->due_date) : null;
        $tp->status      = $request->status;
        $tp->save();

       return redirect(route('teacher.courses.show', $tp->class_id) . '#tps')
         ->with('success', 'TP mis à jour avec succès!');
    }

    public function destroyTP($id)
    {
        $tp = TP::with('class')->findOrFail($id);

        if ($tp->class->teacher_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $courseId = $tp->class_id;
        $tp->delete();

        return redirect()->route('teacher.courses.show', $courseId)
                         ->with('success', 'TP supprimé avec succès!');
    }

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

    public function gradeSubmission(Request $request, $tpId, $submissionId)
    {
        $submission = Submission::whereHas('tp', function($query) {
                                    $query->where('teacher_id', Auth::id());
                                })
                                ->where('tp_id', $tpId)
                                ->findOrFail($submissionId);

        $request->validate([
            'grade'           => 'required|numeric|min:0|max:20',
            'teacher_comment' => 'nullable|string',
        ]);

        $submission->update([
            'grade'           => $request->grade,
            'teacher_comment' => $request->teacher_comment,
            'status'          => 'graded',
        ]);

        if (NotificationSetting::shouldNotify(
            $submission->student_id,
            $submission->tp->class_id,
            'submission_graded'
        )) {
            Notification::createFor(
                $submission->student_id,
                'submission_graded',
                '⭐ TP noté: ' . $submission->tp->title,
                'Votre soumission a été notée: ' . $request->grade . '/20',
                route('student.tps.show', $submission->tp_id),
                $submission->id
            );
        }

        return redirect()->route('teacher.tps.show', $tpId)
                         ->with('success', 'Note enregistrée avec succès!');
    }

    public function studentProgress()
    {
        $teacher = Auth::user();
        $classes = ClassModel::where('teacher_id', $teacher->id)
                             ->with(['students'])
                             ->get();

        return view('teacher.progress.index', compact('classes'));
    }

    public function showStudentProgress($studentId)
    {
        $teacher = Auth::user();
        $student = User::where('role', 'student')->findOrFail($studentId);

        $submissions = Submission::whereHas('tp', function($query) use ($teacher) {
                                    $query->where('teacher_id', $teacher->id);
                                })
                                ->where('student_id', $studentId)
                                ->with('tp')
                                ->orderBy('submitted_at', 'desc')
                                ->get();

        $totalSubmissions  = $submissions->count();
        $gradedSubmissions = $submissions->where('status', 'graded')->count();
        $averageGrade      = $submissions->where('status', 'graded')->avg('grade');

        $attendances = Attendance::where('student_id', $studentId)
                                 ->where('teacher_id', $teacher->id)
                                 ->orderBy('date', 'desc')
                                 ->get();

        $attendanceStats = [
            'total'   => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent'  => $attendances->where('status', 'absent')->count(),
            'late'    => $attendances->where('status', 'late')->count(),
        ];

        return view('teacher.progress.show', compact(
            'student', 'submissions', 'totalSubmissions',
            'gradedSubmissions', 'averageGrade', 'attendances', 'attendanceStats'
        ));
    }

    public function attendanceIndex()
    {
        $teacher = Auth::user();
        $classes = ClassModel::where('teacher_id', $teacher->id)->get();

        return view('teacher.attendance.index', compact('classes'));
    }

    public function attendanceShow(Request $request)
    {
        $teacher = Auth::user();
        $classId = $request->class_id;
        $date    = $request->date ?? now()->format('Y-m-d');

        $class = ClassModel::where('teacher_id', $teacher->id)
                           ->with('students')
                           ->findOrFail($classId);

        $existingAttendances = Attendance::where('class_id', $classId)
                                         ->where('date', $date)
                                         ->get()
                                         ->keyBy('student_id');

        return view('teacher.attendance.take', compact('class', 'date', 'existingAttendances'));
    }

    public function attendanceSave(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'date'       => 'required|date',
            'attendance' => 'required|array',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id'   => $request->class_id,
                    'date'       => $request->date,
                ],
                [
                    'teacher_id' => $teacher->id,
                    'status'     => $status,
                    'notes'      => $request->notes[$studentId] ?? null,
                ]
            );
        }

        return redirect()->route('teacher.attendance.index')
                         ->with('success', 'Présence enregistrée avec succès!');
    }

    public function statistics(Request $request)
{
    $teacher = Auth::user();
    $selectedClassId = $request->class_id;

    $totalTPs     = TP::where('teacher_id', $teacher->id)->count();
    $publishedTPs = TP::where('teacher_id', $teacher->id)->where('status', 'published')->count();
    $draftTPs     = TP::where('teacher_id', $teacher->id)->where('status', 'draft')->count();

    $totalSubmissions = Submission::whereHas('tp', function($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id);
    })->count();

    $gradedSubmissions = Submission::whereHas('tp', function($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id);
    })->where('status', 'graded')->count();

    $pendingSubmissions = Submission::whereHas('tp', function($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id);
    })->where('status', 'submitted')->count();

    $averageGrade = Submission::whereHas('tp', function($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id);
    })->where('status', 'graded')->avg('grade');

    // Grade distribution — filtered by course if selected
    $gradeQuery = Submission::whereHas('tp', function($q) use ($teacher, $selectedClassId) {
        $q->where('teacher_id', $teacher->id);
        if ($selectedClassId) {
            $q->where('class_id', $selectedClassId);
        }
    })->where('status', 'graded')->whereNotNull('grade');

    $gradeDistribution = $gradeQuery->select(
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

    $classes = ClassModel::where('teacher_id', $teacher->id)
                         ->withCount('students')
                         ->get();

    $recentSubmissions = Submission::whereHas('tp', function($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id);
    })
    ->with(['student', 'tp'])
    ->orderBy('submitted_at', 'desc')
    ->limit(10)
    ->get();

    $attendanceStats = Attendance::where('teacher_id', $teacher->id)
                                 ->select('status', DB::raw('COUNT(*) as count'))
                                 ->groupBy('status')
                                 ->get()
                                 ->pluck('count', 'status');

    return view('teacher.statistics.index', compact(
        'totalTPs', 'publishedTPs', 'draftTPs',
        'totalSubmissions', 'gradedSubmissions', 'pendingSubmissions',
        'averageGrade', 'gradeDistribution', 'classes',
        'recentSubmissions', 'attendanceStats', 'selectedClassId'
    ));
}
}