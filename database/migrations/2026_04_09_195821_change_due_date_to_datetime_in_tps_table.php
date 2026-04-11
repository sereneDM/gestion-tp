<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDueDateToDatetimeInTpsTable extends Migration
{
    public function up()
    {
        Schema::table('tps', function (Blueprint $table) {
            $table->dateTime('due_date')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('tps', function (Blueprint $table) {
            $table->date('due_date')->nullable()->change();
        });
    }
}