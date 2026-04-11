<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ClassModel;
use App\Models\Notification;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Str;

class FeedController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();
        $post = Post::with(['user', 'class', 'tp', 'comments.user', 'comments.replies.user'])
                    ->findOrFail($id);

        if ($user->isStudent()) {
            $enrolledClassIds = $user->enrolledClasses()->pluck('classes.id');
            if ($post->class_id && !$enrolledClassIds->contains($post->class_id)) {
                abort(403);
            }
        } elseif ($user->isTeacher()) {
            if ($post->user_id !== $user->id) {
                abort(403);
            }
        }

        return view('feed.show', compact('post'));
    }

    public function storeComment(Request $request, $id)
    {
        $user = Auth::user();
        $post = Post::findOrFail($id);

        $request->validate([
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'post_id'   => $post->id,
            'user_id'   => $user->id,
            'parent_id' => $request->parent_id,
            'content'   => $request->content,
        ]);

        if ($user->id !== $post->user_id) {
            Notification::createFor(
                $post->user_id,
                'new_post',
                '💬 Nouveau commentaire: ' . $post->title,
                $user->name . ' a commenté: ' . Str::limit($request->content, 80),
                route('posts.show', $post->id),
                $post->id
            );
        }

        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment && $parentComment->user_id !== $user->id) {
                Notification::createFor(
                    $parentComment->user_id,
                    'new_post',
                    '↩️ Nouvelle réponse à votre commentaire',
                    $user->name . ' a répondu: ' . Str::limit($request->content, 80),
                    route('posts.show', $post->id),
                    $post->id
                );
            }
        }

        return redirect()->route('posts.show', $post->id)
                         ->with('success', 'Commentaire ajouté!');
    }

    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId)
                         ->with('success', 'Commentaire supprimé!');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $posts = Post::visibleToStudent($user->id)->paginate(10);

            $enrolledCoursesCount = $user->enrolledClasses()->count();
            $availableTPs = \App\Models\TP::whereHas('class', function($query) use ($user) {
                $query->whereHas('students', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            })->where('status', 'published')->count();
            $submittedCount = \App\Models\Submission::where('student_id', $user->id)->count();
            $gradedCount = \App\Models\Submission::where('student_id', $user->id)
                                      ->whereNotNull('grade')->count();

            return view('student.dashboard', compact('posts', 'enrolledCoursesCount', 'availableTPs', 'submittedCount', 'gradedCount'));
        } else {
            $posts = Post::where('user_id', $user->id)
                         ->with(['class.students', 'tp'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

            $courses = ClassModel::where('teacher_id', $user->id)->with('students')->get();

            return view('teacher.feed.teacher', compact('posts', 'courses'));
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403, 'Seuls les enseignants peuvent créer des posts');
        }

        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'class_id'   => 'nullable|exists:classes,id',
            'type'       => 'required|in:announcement,reminder,general',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,zip|max:10240',
        ]);

        if ($request->class_id) {
            $class = ClassModel::where('id', $request->class_id)
                               ->where('teacher_id', $user->id)
                               ->firstOrFail();
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('post_attachments', $filename, 'public');
        }

        $post = Post::create([
            'user_id'    => $user->id,
            'class_id'   => $request->class_id,
            'type'       => $request->type,
            'title'      => $request->title,
            'content'    => $request->content,
            'attachment' => $attachmentPath,
        ]);

        // Notify students — both for class-specific and general posts
        $this->notifyStudents($post);

        return redirect()->route('feed.index')
                         ->with('success', 'Post publié avec succès!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        if ($post->attachment) {
            Storage::disk('public')->delete($post->attachment);
        }

        $post->delete();

        return redirect()->route('feed.index')
                         ->with('success', 'Post supprimé avec succès!');
    }

    private function notifyStudents($post)
{
    $teacher = Auth::user();

    if ($post->class_id) {
        $class = ClassModel::with('students')->find($post->class_id);
        $students = $class->students;
    } else {
        // Fix: query students directly from pivot table
        $teacherClassIds = ClassModel::where('teacher_id', $teacher->id)->pluck('id');
        $students = User::whereHas('classes', function($q) use ($teacherClassIds) {
            $q->whereIn('classes.id', $teacherClassIds);
        })->get();
    }

    foreach ($students as $student) {
        if (NotificationSetting::shouldNotify($student->id, $post->class_id, 'post')) {
            Notification::createFor(
                $student->id,
                'new_post',
                '📢 Nouvelle annonce: ' . $post->title,
                $post->content,
                route('posts.show', $post->id),
                $post->id
            );
        }
    }
}
}