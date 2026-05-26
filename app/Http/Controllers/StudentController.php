<?php

namespace App\Http\Controllers;

use App\Models\TP;
use App\Models\ClassModel;
use App\Models\Submission;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user();

        $posts = \App\Models\Post::visibleToStudent($student->id)->paginate(10);

        $enrolledCoursesCount = $student->enrolledClasses()
            ->where('status', 'active')
            ->count();

        $availableTPs = TP::whereHas('class', function($query) use ($student) {
            $query->where('status', 'active')
                  ->whereHas('students', function($q) use ($student) {
                      $q->where('users.id', $student->id);
                  });
        })->where('status', 'published')->count();

        $submittedCount = Submission::where('student_id', $student->id)->count();

        $gradedCount = Submission::where('student_id', $student->id)
                                  ->whereNotNull('grade')->count();

        return view('student.dashboard', compact(
            'enrolledCoursesCount',
            'availableTPs',
            'submittedCount',
            'gradedCount',
            'posts'
        ));
    }

    public function showJoinCourse()
    {
        return view('student.courses.join');
    }

    public function joinCourse(Request $request)
    {
        $request->validate([
            'join_code' => 'required|string',
        ]);

        $course = ClassModel::where('join_code', strtoupper($request->join_code))
                            ->where('status', 'active')
                            ->first();

        if (!$course) {
            return back()->withErrors(['join_code' => 'Code invalide ou cours non actif.']);
        }

        if ($course->students()->where('users.id', Auth::id())->exists()) {
            return back()->withErrors(['join_code' => 'Vous êtes déjà inscrit à ce cours.']);
        }

        $course->students()->attach(Auth::id());

        if (NotificationSetting::shouldNotify($course->teacher_id, $course->id, 'student_joined')) {
            Notification::createFor(
                $course->teacher_id,
                'student_joined',
                '👤 Nouvel étudiant: ' . Auth::user()->name,
                Auth::user()->name . ' a rejoint le cours ' . $course->name,
                route('teacher.courses.show', $course->id) . '?tab=students',
                $course->id
            );
        }

        return redirect()->route('student.my-courses')
                         ->with('success', 'Vous avez rejoint le cours: ' . $course->name);
    }

    public function myCourses()
    {
        $student = Auth::user();

        $courses = $student->enrolledClasses()
            ->where('status', 'active')
            ->with([
                'teacher',
                'tps' => function ($query) {
                    $query->where('status', 'published')
                          ->distinct()
                          ->orderBy('created_at', 'desc');
                }
            ])
            ->withCount('tps')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.courses.index', compact('courses'));
    }

    public function showCourse($courseId)
    {
        $student = Auth::user();

        $course = ClassModel::whereHas('students', function ($query) use ($student) {
                $query->where('users.id', $student->id);
            })
            ->where('status', 'active')
            ->with([
                'teacher',
                'tps' => function ($query) {
                    $query->where('status', 'published')
                          ->distinct()
                          ->orderBy('created_at', 'desc');
                }
            ])
            ->findOrFail($courseId);

        $tpIds = $course->tps->pluck('id')->unique();

        $submissions = Submission::where('student_id', $student->id)
            ->whereIn('tp_id', $tpIds)
            ->get()
            ->keyBy('tp_id');

        return view('student.courses.show', compact('course', 'submissions'));
    }

    public function leaveCourse($courseId)
    {
        $student = Auth::user();
        $course = ClassModel::findOrFail($courseId);
        $course->students()->detach($student->id);

        return redirect()->route('student.my-courses')
                         ->with('success', 'Vous avez quitté le cours: ' . $course->name);
    }

    public function showTP($id)
    {
        $student = Auth::user();

        $tp = TP::with(['class', 'teacher'])
            ->whereHas('class', fn($q) => $q->where('status', 'active'))
            ->whereHas('class.students', function ($query) use ($student) {
                $query->where('users.id', $student->id);
            })
            ->where('id', $id)
            ->firstOrFail();

        $submission = Submission::where('tp_id', $tp->id)
            ->where('student_id', $student->id)
            ->first();

        return view('student.tps.show', compact('tp', 'submission'));
    }

    public function submitTP(Request $request, $id)
    {
        $student = Auth::user();

        $tp = TP::where('id', $id)
                ->whereHas('class', fn($q) => $q->where('status', 'active'))
                ->whereHas('class.students', fn($q) => $q->where('users.id', $student->id))
                ->firstOrFail();

        if ($tp->status === 'closed') {
            return back()->withErrors(['error' => 'Ce TP n\'accepte plus de soumissions.']);
        }

        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,zip,doc,docx|max:51200',
            'comments'        => 'nullable|string',
        ]);

        if (Submission::where('tp_id', $tp->id)->where('student_id', $student->id)->exists()) {
            return back()->withErrors(['error' => 'Vous avez déjà soumis ce TP.']);
        }

        $file     = $request->file('submission_file');
        $filename = uniqid($student->id . '_') . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('submissions', $filename, 'public');

        $submission = Submission::create([
            'tp_id'        => $tp->id,
            'student_id'   => $student->id,
            'attachments'  => $filePath,
            'content'      => $request->comments,
            'submitted_at' => now(),
            'status'       => 'submitted',
        ]);

        if (NotificationSetting::shouldNotify($tp->teacher_id, $tp->class_id, 'new_submission')) {
            Notification::createFor(
                $tp->teacher_id,
                'new_submission',
                '📤 Nouvelle soumission: ' . $tp->title,
                $student->name . ' a soumis le TP',
                route('teacher.submissions.show', [$tp->id, $submission->id]),
                $submission->id
            );
        }

        return redirect()->route('student.tps.show', $tp->id);
    }

    public function updateSubmission(Request $request, $id)
    {
        $student = Auth::user();

        $tp = TP::where('id', $id)
                ->whereHas('class', fn($q) => $q->where('status', 'active'))
                ->whereHas('class.students', fn($q) => $q->where('users.id', $student->id))
                ->firstOrFail();

        if ($tp->due_date && now()->gt($tp->due_date)) {
            return back()->withErrors(['error' => 'La date limite est dépassée.']);
        }

        $submission = Submission::where('tp_id', $tp->id)
                                ->where('student_id', $student->id)
                                ->firstOrFail();

        if ($submission->grade) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas modifier une soumission déjà notée.']);
        }

        $request->validate([
            'submission_file' => 'nullable|file|mimes:pdf,zip,doc,docx|max:51200',
            'comments'        => 'nullable|string',
        ]);

        if ($request->hasFile('submission_file')) {
            $file     = $request->file('submission_file');
            $filename = uniqid($student->id . '_') . '.' . $file->getClientOriginalExtension();
            $submission->attachments = $file->storeAs('submissions', $filename, 'public');
        }

        $submission->content      = $request->comments;
        $submission->submitted_at = now();
        $submission->save();

        return redirect()->route('student.tps.show', $tp->id)
                         ->with('success', 'Soumission modifiée avec succès.');
    }

    public function mySubmissions()
    {
        $student = Auth::user();
        $submissions = Submission::where('student_id', $student->id)
                                 ->with(['tp.class', 'tp.teacher'])
                                 ->orderBy('submitted_at', 'desc')
                                 ->get();

        return view('student.submissions.index', compact('submissions'));
    }

    public function myProgress()
    {
        $student = Auth::user();

        $allSubmissions = Submission::where('student_id', $student->id)
            ->with(['tp.teacher', 'tp.class'])
            ->orderBy('submitted_at', 'desc')
            ->get();

        $totalSubmissions   = $allSubmissions->count();
        $gradedSubmissions  = $allSubmissions->whereNotNull('grade')->count();
        $pendingSubmissions = $allSubmissions->whereNull('grade')->count();

        $totalGrade   = $allSubmissions->whereNotNull('grade')->sum('grade');
        $averageGrade = $gradedSubmissions > 0 ? $totalGrade / $gradedSubmissions : 0;

        $gradesByTP = $allSubmissions->whereNotNull('grade')->map(function($s) {
            return [
                'tp'           => $s->tp,
                'grade'        => $s->grade,
                'submitted_at' => $s->submitted_at,
            ];
        });

        $attendances = Attendance::where('student_id', $student->id)
            ->with('class')
            ->orderBy('date', 'desc')
            ->get();

        $attendanceStats = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent'  => $attendances->where('status', 'absent')->count(),
            'late'    => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
        ];

        return view('student.progress.index', compact(
            'totalSubmissions',
            'gradedSubmissions',
            'pendingSubmissions',
            'averageGrade',
            'attendanceStats',
            'gradesByTP',
            'attendances'
        ));
    }
}