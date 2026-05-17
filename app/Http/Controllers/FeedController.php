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
        $post = Post::with([
                        'user', 'class', 'tp',
                        'comments.user',
                        'comments' => function ($q) {
                            $q->withCount('likes')
                              ->withExists(['likes as is_liked' => fn($q) => $q->where('user_id', auth()->id())]);
                        },
                        'comments.replies.user',
                        'comments.replies' => function ($q) {
                            $q->withCount('likes')
                              ->withExists(['likes as is_liked' => fn($q) => $q->where('user_id', auth()->id())]);
                        },
                    ])
                    ->withCount('likes')
                    ->withExists([
                        'likes as is_liked' => function ($q) {
                            $q->where('user_id', auth()->id());
                        }
                    ])
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

        // Link anchors directly to the new comment
        $commentAnchor = route('posts.show', $post->id) . '#comment-' . $comment->id;

        // Notify post author (if not self)
        if ($user->id !== $post->user_id) {
            if (NotificationSetting::shouldNotify($post->user_id, $post->class_id, 'comment')) {
                Notification::createFor(
                    $post->user_id,
                    'new_comment',
                    '💬 Nouveau commentaire: ' . $post->title,
                    $user->name . ' a commenté: ' . Str::limit($request->content, 80),
                    $commentAnchor,
                    $comment->id
                );
            }
        }

        // Notify parent comment author if this is a reply (if not self)
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment && $parentComment->user_id !== $user->id) {
                if (NotificationSetting::shouldNotify($parentComment->user_id, $post->class_id, 'comment')) {
                    Notification::createFor(
                        $parentComment->user_id,
                        'new_comment',
                        '↩️ Nouvelle réponse à votre commentaire',
                        $user->name . ' a répondu: ' . Str::limit($request->content, 80),
                        $commentAnchor,
                        $comment->id
                    );
                }
            }
        }

        $scrollTo = $request->parent_id
            ? 'comment-' . $request->parent_id
            : 'comment-' . $comment->id;

        return redirect()->route('posts.show', $post->id)
                         ->with('success', 'Commentaire ajouté!')
                         ->with('new_comment_id', $comment->id)
                         ->with('scroll_to', $scrollTo);
    }

    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $postId   = $comment->post_id;
        $parentId = $comment->parent_id;
        $comment->delete();

        $scrollTo = $parentId ? 'comment-' . $parentId : 'comments';

        return redirect()->route('posts.show', $postId)
                         ->with('success', 'Commentaire supprimé!')
                         ->with('scroll_to', $scrollTo);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $posts = Post::visibleToStudent($user->id)
                         ->with(['comments.replies'])
                         ->withCount('likes')
                         ->withExists([
                             'likes as is_liked' => function ($q) {
                                 $q->where('user_id', auth()->id());
                             }
                         ])
                         ->latest()
                         ->paginate(10);

            $enrolledCoursesCount = $user->enrolledClasses()->count();
            $availableTPs = \App\Models\TP::whereHas('class', function($query) use ($user) {
                $query->whereHas('students', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            })->where('status', 'published')->count();
            $submittedCount = \App\Models\Submission::where('student_id', $user->id)->count();
            $gradedCount    = \App\Models\Submission::where('student_id', $user->id)->whereNotNull('grade')->count();

            return view('student.dashboard', compact(
                'posts', 'enrolledCoursesCount', 'availableTPs', 'submittedCount', 'gradedCount'
            ));
        } else {
            $posts = Post::where('user_id', $user->id)
                         ->with(['class.students', 'tp', 'comments.replies'])
                         ->withCount('likes')
                         ->withExists([
                             'likes as is_liked' => function ($q) {
                                 $q->where('user_id', auth()->id());
                             }
                         ])
                         ->latest()
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
        'title'       => 'required|string|max:100',
        'content'     => 'required|string|max:2000',
        'class_ids'   => 'nullable|array',
        'class_ids.*' => 'exists:classes,id',
        'type'        => 'required|in:announcement,reminder,general',
        'attachment'  => 'nullable|file|mimes:pdf,jpg,png,zip|max:10240',
    ]);

    $classIds = $request->class_ids ?? [];

    // Verify teacher owns all selected courses
    if (!empty($classIds)) {
        $ownedCount = ClassModel::whereIn('id', $classIds)
                                ->where('teacher_id', $user->id)
                                ->count();
        if ($ownedCount !== count($classIds)) {
            abort(403, 'Vous ne pouvez publier que dans vos propres cours');
        }
    }

    // Handle attachment once, reuse path for all posts
    $attachmentPath = null;
    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $attachmentPath = $file->storeAs(
            'post_attachments',
            time() . '_' . $file->getClientOriginalName(),
            'public'
        );
    }

    // One post per course, or one general post if none selected
    $targets = !empty($classIds) ? $classIds : [null];

    foreach ($targets as $classId) {
        $post = Post::create([
            'user_id'    => $user->id,
            'class_id'   => $classId,
            'type'       => $request->type,
            'title'      => $request->title,
            'content'    => $request->content,
            'attachment' => $attachmentPath,
        ]);

        $this->notifyStudents($post);
    }

    return redirect()->route('feed.index')->with('success', 'Post publié avec succès!');
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

        return redirect()->route('feed.index')->with('success', 'Post supprimé avec succès!');
    }

    private function notifyStudents($post)
    {
        $teacher = Auth::user();

        if ($post->class_id) {
            $students = ClassModel::with('students')->find($post->class_id)->students;
        } else {
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
    public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

    if ($post->user_id !== Auth::id()) {
        abort(403);
    }

    $request->validate([
        'title'      => 'required|string|max:50',
        'content'    => 'required|string',
        'type'       => 'required|in:announcement,reminder,general',
        'attachment' => 'nullable|file|mimes:pdf,jpg,png,zip|max:10240',
    ]);

    // Handle attachment removal
    if ($request->has('remove_attachment') && $post->attachment) {
        Storage::disk('public')->delete($post->attachment);
        $post->attachment = null;
    }

    // Handle new attachment upload
    if ($request->hasFile('attachment')) {
        // Delete old one first
        if ($post->attachment) {
            Storage::disk('public')->delete($post->attachment);
        }
        $file = $request->file('attachment');
        $post->attachment = $file->storeAs(
            'post_attachments',
            time() . '_' . $file->getClientOriginalName(),
            'public'
        );
    }

    $post->title   = $request->title;
    $post->content = $request->content;
    $post->type    = $request->type;
    $post->save();

    return redirect()->route('posts.show', $post->id)->with('success', 'Publication modifiée!');
}
}