<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'join_code',
        'status',
    ];

    // Automatically generate join code when creating a class
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($class) {
            if (!$class->join_code) {
                $class->join_code = self::generateUniqueJoinCode();
            }
        });
    }

    // Generate unique join code
    public static function generateUniqueJoinCode()
    {
        do {
            $code = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . rand(100, 999));
        } while (self::where('join_code', $code)->exists());
        
        return $code;
    }

    // Regenerate join code
    public function regenerateJoinCode()
    {
        $this->join_code = self::generateUniqueJoinCode();
        $this->save();
        return $this->join_code;
    }

    // Relationship: A class belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relationship: A class has many students
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'student_id')
                    ->withTimestamps();
    }

    // Scope for active classes only
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for teacher's classes
    public function scopeOwnedBy($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
}