<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BenefitEmployee extends Migration
{
    public function up()
    {
        Schema::create('benefit_employee', function (Blueprint $table) {
            
            $table->bigInteger('benefit_id')->unsigned()->nullable();
            $table->foreign('benefit_id')->references('id')->on('benefits')->onDelete('cascade');
    
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            $table->string('ref_number')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('benefit_employee');
    }
}
