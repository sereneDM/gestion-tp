<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'class_id',
        'new_tp_notifications',
        'submission_graded_notifications',
        'new_submission_notifications',
        'post_notifications',
    ];

    protected $casts = [
        'new_tp_notifications' => 'boolean',
        'submission_graded_notifications' => 'boolean',
        'new_submission_notifications' => 'boolean',
        'post_notifications' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courseClass()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // Get or create settings for a user and course
    public static function getFor($userId, $classId = null)
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'class_id' => $classId],
            [
                'new_tp_notifications' => true,
                'submission_graded_notifications' => true,
                'new_submission_notifications' => true,
                'post_notifications' => true,
            ]
        );
    }

    // Check if user wants notifications for this type
    public static function shouldNotify($userId, $classId, $type)
    {
        $setting = self::getFor($userId, $classId);
        return $setting->{$type . '_notifications'} ?? true;
    }
}