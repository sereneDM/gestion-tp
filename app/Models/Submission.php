<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'tp_id',
        'student_id',
        'content',
        'attachments',
        'grade',
        'teacher_comment',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function tp()
    {
         return $this->belongsTo(TP::class, 'tp_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}