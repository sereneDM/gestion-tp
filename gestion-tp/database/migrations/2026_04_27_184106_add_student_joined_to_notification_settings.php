<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->boolean('student_joined_notifications')->default(true);
        });
    }

    public function down()
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->dropColumn('student_joined_notifications');
        });
    }
};