<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Show all notifications
    public function index()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(20);

        $unreadCount = Notification::where('user_id', $user->id)
                                   ->where('is_read', false)
                                   ->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    // Mark single notification as read and redirect to its link
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
                                    ->findOrFail($id);

        $notification->markAsRead();

        if ($notification->link && $notification->link !== url('/')) {
            return redirect($notification->link);
        }

        return redirect()->route(
            Auth::user()->isTeacher() ? 'feed.index' : 'student.dashboard'
        );
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        return redirect()->route('notifications.index')
                         ->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    // Show notification settings
    public function settings()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $courses = $user->enrolledClasses()->get();
        } else {
            $courses = ClassModel::where('teacher_id', $user->id)->get();
        }

        $settings = [];
        foreach ($courses as $course) {
            $settings[$course->id] = NotificationSetting::getFor($user->id, $course->id);
        }

        $globalSettings = NotificationSetting::getFor($user->id, null);

        return view('notifications.settings', compact('courses', 'settings', 'globalSettings'));
    }

    // Update notification settings
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        // Update global settings
        $globalSetting = NotificationSetting::getFor($user->id, null);
        $globalSetting->update([
            'new_tp_notifications'            => $request->has('global_new_tp'),
            'submission_graded_notifications' => $request->has('global_submission_graded'),
            'new_submission_notifications'    => $request->has('global_new_submission'),
            'post_notifications'              => $request->has('global_post'),
            'student_joined_notifications'    => $request->has('global_student_joined'),
            'comment_notifications'           => $request->has('global_comment'),
            'like_notifications'              => $request->has('global_like'),
            'comment_like_notifications'      => $request->has('global_comment_like'),
        ]);

        // Update per-course settings
        if ($request->has('courses')) {
            foreach ($request->courses as $classId => $settings) {
                $setting = NotificationSetting::getFor($user->id, $classId);
                $setting->update([
                    'new_tp_notifications'            => isset($settings['new_tp']),
                    'submission_graded_notifications' => isset($settings['submission_graded']),
                    'new_submission_notifications'    => isset($settings['new_submission']),
                    'post_notifications'              => isset($settings['post']),
                    'student_joined_notifications'    => isset($settings['student_joined']),
                    'comment_notifications'           => isset($settings['comment']),
                    'like_notifications'              => isset($settings['like']),
                    'comment_like_notifications'      => isset($settings['comment_like']),
                ]);
            }
        }

        return redirect()->route('notifications.index')
                         ->with('success', 'Paramètres de notification mis à jour!');
    }
}