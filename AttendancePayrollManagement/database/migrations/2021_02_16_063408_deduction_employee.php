<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeductionEmployee extends Migration
{
    
    public function up()
    {
        Schema::create('deduction_employee', function (Blueprint $table) {
            
            $table->bigInteger('deduction_id')->unsigned()->nullable();
            $table->foreign('deduction_id')->references('id')->on('deductions')->onDelete('cascade');
    
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->timestamp('date_deducted');
        });
    }

    public function down()
    {
        Schema::dropIfExists('deduction_employee');
    }
}
