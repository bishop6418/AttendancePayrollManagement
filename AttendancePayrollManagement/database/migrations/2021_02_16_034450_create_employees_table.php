<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{

    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {

            $table->id();

            $table->bigInteger('position_id')->unsigned()->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');

            $table->bigInteger('rate_id')->unsigned()->nullable();
            $table->foreign('rate_id')->references('id')->on('rates')->onDelete('set null');

            $table->bigInteger('schedule_id')->unsigned()->nullable();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('set null');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->bigInteger('age');
            $table->string('address');
            $table->string('contact_number');
            $table->string('gender');
            $table->date('birthdate');
            $table->string('marital_status');
            $table->date('date_hired');
            $table->bigInteger('leave_credits')->default(0);
            $table->longText('image_path')->default('');
            $table->string('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
