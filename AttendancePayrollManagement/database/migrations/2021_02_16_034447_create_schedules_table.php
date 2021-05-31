<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
   
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('shift');
            $table->time('time_in');
            $table->time('time_out');
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
