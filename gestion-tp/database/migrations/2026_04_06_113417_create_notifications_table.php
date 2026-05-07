<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Who receives this notification
            $table->enum('type', ['new_tp', 'submission_graded', 'new_submission', 'new_post', 'reminder'])->default('new_post');
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable(); // URL to relevant page
            $table->unsignedBigInteger('related_id')->nullable(); // ID of related item (TP, submission, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};