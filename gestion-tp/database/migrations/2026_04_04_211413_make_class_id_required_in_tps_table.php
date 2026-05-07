<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First, delete any TPs without a class_id
        DB::table('tps')->whereNull('class_id')->delete();
        
        Schema::table('tps', function (Blueprint $table) {
            // Make class_id required
            $table->unsignedBigInteger('class_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tps', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->nullable()->change();
        });
    }
};