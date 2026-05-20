<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration is empty - the column was already added in the previous migration
        // Keeping this for migration history integrity
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op - column removal is handled by the previous migration
    }
};
