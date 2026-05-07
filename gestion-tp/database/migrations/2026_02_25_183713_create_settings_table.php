<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('settings'); // Add this line
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Setting name (e.g., 'site_name', 'semester_start')
            $table->text('value')->nullable(); // Setting value
            $table->string('type')->default('text'); // Type: text, number, date, boolean
            $table->text('description')->nullable(); // Description of what this setting does
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};