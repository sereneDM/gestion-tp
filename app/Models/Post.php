<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function tp()
    {
        return $this->belongsTo(TP::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
                    ->whereNull('parent_id')
                    ->with(['user', 'replies'])
                    ->orderBy('created_at', 'asc');
    }

  public static function visibleToStudent($studentId)
{
    $student = User::find($studentId);
    $enrolledClassIds = $student->enrolledClasses()->pluck('classes.id');

    // Get teacher IDs of enrolled classes
    $teacherIds = ClassModel::whereIn('id', $enrolledClassIds)->pluck('teacher_id');

    return self::with(['user', 'class', 'tp'])
               ->where(function($query) use ($enrolledClassIds, $teacherIds) {
                   // Posts from enrolled classes
                   $query->whereIn('class_id', $enrolledClassIds)
                         // OR general posts from their teachers
                         ->orWhere(function($q) use ($teacherIds) {
                             $q->whereNull('class_id')
                               ->whereIn('user_id', $teacherIds);
                         });
               })
               ->orderBy('created_at', 'desc');
}
}