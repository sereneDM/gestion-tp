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
use Illuminate\Support\Str;



class FeedController extends Controller
{
    // Show single post with comments
public function show($id)
{
    $user = Auth::user();
    $post = Post::with(['user', 'class', 'tp', 'comments.user', 'comments.replies.user'])
                ->findOrFail($id);

    // Check access: teacher who owns it, or student enrolled in the class
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

// Store a comment or reply
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

    // Notify post owner (teacher) about new comment
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

    // If this is a reply, notify the parent comment author
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

// Delete a comment
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
    // Show feed
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isStudent()) {
            // Student sees posts from enrolled courses
            $posts = Post::visibleToStudent($user->id)->paginate(10);
            
            // Get stats for dashboard
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
            // Teacher sees all their posts
           $posts = Post::where('user_id', $user->id)
             ->with(['class.students', 'tp'])
             ->orderBy('created_at', 'desc')
             ->paginate(10);
            
           $courses = ClassModel::where('teacher_id', $user->id)->with('students')->get();
            
           return view('teacher.feed.teacher', compact('posts', 'courses'));
        }
    }

    // Create post (teacher only)
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isTeacher()) {
            abort(403, 'Seuls les enseignants peuvent créer des posts');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'class_id' => 'nullable|exists:classes,id',
            'type' => 'required|in:announcement,reminder,general',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,zip|max:10240',
        ]);

        // Verify teacher owns the class
        if ($request->class_id) {
            $class = ClassModel::where('id', $request->class_id)
                               ->where('teacher_id', $user->id)
                               ->firstOrFail();
        }

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('post_attachments', $filename, 'public');
        }

        // Create post
        $post = Post::create([
            'user_id' => $user->id,
            'class_id' => $request->class_id,
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content,
            'attachment' => $attachmentPath,
        ]);

        // Send notifications to students
        if ($request->class_id) {
            $this->notifyStudents($post);
        }

        return redirect()->route('feed.index')
                         ->with('success', 'Post publié avec succès!');
    }

    // Delete post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Only post creator can delete
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete attachment if exists
        if ($post->attachment) {
            Storage::disk('public')->delete($post->attachment);
        }

        $post->delete();

        return redirect()->route('feed.index')
                         ->with('success', 'Post supprimé avec succès!');
    }

    // Notify students about new post
    private function notifyStudents($post)
    {
        if (!$post->class_id) return;

        $class = ClassModel::with('students')->find($post->class_id);
        
        foreach ($class->students as $student) {
            // Check if student wants notifications
            if (NotificationSetting::shouldNotify($student->id, $post->class_id, 'post')) {
                Notification::createFor(
                    $student->id,
                    'new_post',
                    '📢 Nouvelle annonce: ' . $post->title,
                    $post->content,
                    route('feed.index'),
                    $post->id
                );
            }
        }
    }
}