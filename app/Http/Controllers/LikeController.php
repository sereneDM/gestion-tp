<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        return $this->handleToggle($post, 'post');
    }

    public function toggleComment(Comment $comment)
    {
        return $this->handleToggle($comment, 'comment');
    }

    protected function handleToggle($likeable, string $type)
    {
        $userId   = Auth::id();
        $existing = $likeable->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $likeable->likes()->create(['user_id' => $userId]);
            $liked = true;
            $this->sendLikeNotification($likeable, $type, $userId);
        }

        return response()->json([
            'liked' => $liked,
            'count' => $likeable->likes()->count(),
        ]);
    }

    protected function sendLikeNotification($likeable, string $type, int $userId): void
    {
        if ($userId === $likeable->user_id) {
            return;
        }

        if ($type === 'post') {
            if (! NotificationSetting::shouldNotify($likeable->user_id, $likeable->class_id, 'like')) {
                return;
            }

            Notification::createFor(
                $likeable->user_id,
                'post_liked',
                Auth::user()->name . ' a aimé votre publication',
                Str::limit($likeable->title ?? '', 80),
                route('posts.show', $likeable->id),
                $likeable->id
            );

            return;
        }

        $post   = $likeable->post;
        $anchor = route('posts.show', $post->id) . '#comment-' . $likeable->id;

        if (! NotificationSetting::shouldNotify($likeable->user_id, $post->class_id, 'comment_like')) {
            return;
        }

        Notification::createFor(
            $likeable->user_id,
            'comment_liked',
            Auth::user()->name . ' a aimé votre commentaire',
            Str::limit($likeable->content ?? '', 80),
            $anchor,
            $likeable->id
        );
    }
}
