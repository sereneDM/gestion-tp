<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Who posted (teacher)
            $table->unsignedBigInteger('class_id')->nullable(); // Which course (null = general announcement)
            $table->unsignedBigInteger('tp_id')->nullable(); // Related TP (if any)
            $table->enum('type', ['announcement', 'tp_posted', 'reminder', 'general'])->default('general');
            $table->string('title');
            $table->text('content');
            $table->string('attachment')->nullable(); // Optional file
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('tp_id')->references('id')->on('tps')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};