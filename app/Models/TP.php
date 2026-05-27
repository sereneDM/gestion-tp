<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TP extends Model
{
    protected $table = 'tps';

    protected $fillable = [
        'title',
        'description',
        'teacher_id',
        'class_id',
        'due_date',
        'status',
        'attachments',
    ];

    protected $casts = [

        'due_date' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function submissions()
    {
         return $this->hasMany(Submission::class, 'tp_id');
    }
}