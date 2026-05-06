<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('class_id')->nullable(); // Per-course settings
            $table->boolean('new_tp_notifications')->default(true);
            $table->boolean('submission_graded_notifications')->default(true);
            $table->boolean('new_submission_notifications')->default(true);
            $table->boolean('post_notifications')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            
            // One setting per user per class
            $table->unique(['user_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};