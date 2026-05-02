<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\Tp;
use App\Models\Comment;
use App\Models\Like;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'class_id',
        'tp_id',
        'type',
        'title',
        'content',
        'attachment',
    ];

    /**
     * Author of the post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Related class (course)
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Related TP
     */
    public function tp(): BelongsTo
    {
        return $this->belongsTo(Tp::class);
    }

    /**
     * Comments (non-polymorphic)
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')
                    ->whereNull('parent_id')
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Likes (polymorphic)
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check if user liked this post
     */
    public function isLikedBy($userId): bool
    {
        if (!$userId) return false;

        if ($this->relationLoaded('likes')) {
            return $this->likes->contains('user_id', $userId);
        }

        return $this->likes()
                    ->where('user_id', $userId)
                    ->exists();
    }

    /**
     * Get posts visible to a student
     */
    public static function visibleToStudent($studentId)
    {
        return self::where(function ($query) use ($studentId) {

            // Posts linked to classes where student is enrolled
            $query->whereHas('class', function ($q) use ($studentId) {
                $q->whereHas('students', function ($q2) use ($studentId) {
                    $q2->where('users.id', $studentId);
                });
            })

            // OR public posts (no class assigned)
            ->orWhereNull('class_id');

        })
        ->with(['class', 'tp', 'likes', 'comments'])
        ->orderBy('created_at', 'desc');
    }
}