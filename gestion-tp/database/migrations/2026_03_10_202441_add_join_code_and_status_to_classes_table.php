<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('join_code', 12)->unique()->after('teacher_id');
            $table->enum('status', ['active', 'archived'])->default('active')->after('join_code');
        });

        // Generate join codes for existing classes
        DB::table('classes')->get()->each(function ($class) {
            DB::table('classes')
                ->where('id', $class->id)
                ->update(['join_code' => $this->generateJoinCode()]);
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['join_code', 'status']);
        });
    }

    private function generateJoinCode()
    {
        do {
            $code = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . rand(100, 999));
        } while (DB::table('classes')->where('join_code', $code)->exists());
        
        return $code;
    }
};