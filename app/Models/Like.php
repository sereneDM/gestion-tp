<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Like extends Model
{
    protected $fillable = ['user_id'];

    /**
     * Get the parent likeable model (Post, Comment, etc.)
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The user who made the like
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}