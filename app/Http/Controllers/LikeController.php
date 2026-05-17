<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $userId   = auth()->id();
        $existing = $post->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => $userId]);
            $liked = true;

            // Notify post author (not self)
            if ($userId !== $post->user_id) {
                if (NotificationSetting::shouldNotify($post->user_id, $post->class_id, 'like')) {
                    Notification::createFor(
                        $post->user_id,
                        'post_liked', // ← was 'comment_liked', now distinct type
                        '❤️ ' . auth()->user()->name . ' a aimé votre publication',
                        Str::limit($post->title, 80),
                        route('posts.show', $post->id),
                        $post->id
                    );
                }
            }
        }

        return response()->json([
            'liked' => $liked,
            'count' => $post->likes()->count(),
        ]);
    }

    public function toggleComment(Comment $comment)
    {
        $userId   = auth()->id();
        $existing = $comment->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => $userId]);
            $liked = true;

            // Notify comment author (not self)
            if ($userId !== $comment->user_id) {
                $post   = $comment->post;
                $anchor = route('posts.show', $post->id) . '#comment-' . $comment->id;

                if (NotificationSetting::shouldNotify($comment->user_id, $post->class_id, 'comment_like')) {
                    Notification::createFor(
                        $comment->user_id,
                        'comment_liked',
                        '❤️ ' . auth()->user()->name . ' a aimé votre commentaire',
                        Str::limit($comment->content, 80),
                        $anchor,
                        $comment->id
                    );
                }
            }
        }

        return response()->json([
            'liked' => $liked,
            'count' => $comment->likes()->count(),
        ]);
    }
}