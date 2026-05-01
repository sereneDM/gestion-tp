<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add new setting columns
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->boolean('comment_notifications')->default(true);
            $table->boolean('like_notifications')->default(true);
        });

        // Expand the notifications type enum
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM(
            'new_tp',
            'submission_graded',
            'new_submission',
            'new_post',
            'reminder',
            'student_joined',
            'new_comment',
            'comment_liked'
        ) NOT NULL DEFAULT 'new_post'");
    }

    public function down()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->dropColumn(['comment_notifications', 'like_notifications']);
        });

        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM(
            'new_tp',
            'submission_graded',
            'new_submission',
            'new_post',
            'reminder',
            'student_joined'
        ) NOT NULL DEFAULT 'new_post'");
    }
};