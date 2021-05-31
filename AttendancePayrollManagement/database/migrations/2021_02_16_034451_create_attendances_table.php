<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{

    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            
            $table->id();
            
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');

            $table->time('time_in_am')->nullable();
            $table->time('time_out_am')->nullable();
            $table->string('status_am')->nullable();
            $table->time('time_in_pm')->nullable();
            $table->time('time_out_pm')->nullable();
            $table->string('status_pm')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
