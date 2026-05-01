<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    public function likes()
{
    return $this->morphMany(Like::class, 'likeable');
}

public function isLikedBy($userId)
{
    return $this->likes()->where('user_id', $userId)->exists();
}
}