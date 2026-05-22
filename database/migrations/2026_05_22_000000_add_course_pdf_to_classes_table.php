<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('course_pdf')->nullable()->after('description');
            $table->string('course_doc_id')->nullable()->after('course_pdf');
        });
    }

    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['course_pdf', 'course_doc_id']);
        });
    }
};
