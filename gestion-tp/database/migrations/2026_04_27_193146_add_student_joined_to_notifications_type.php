<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM(
        'new_tp',
        'submission_graded',
        'new_submission',
        'new_post',
        'reminder',
        'student_joined'
    ) NOT NULL DEFAULT 'new_post'");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications_type', function (Blueprint $table) {
            //
        });
    }
};
