<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayslipsTable extends Migration
{
    
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('payroll_id')->unsigned()->nullable();
            $table->foreign('payroll_id')->references('id')->on('payrolls')->onDelete('cascade');

            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->bigInteger('total_weekdays');
            $table->bigInteger('counts_late');
            $table->decimal('days_absent', 10, 2)->default(0.00);
            $table->decimal('days_leave', 10, 2)->default(0.00);
            $table->decimal('days_worked', 10, 2)->default(0.00);
            $table->decimal('days_saturday', 10, 2)->default(0.00);

            $table->decimal('basic_salary', 10, 2)->default(0.00);
            $table->decimal('additional', 10, 2)->default(0.00);
            $table->decimal('leave_pay', 10, 2)->default(0.00);
            $table->decimal('saturday_pay', 10, 2)->default(0.00);

            // $table->decimal('deduc_late', 10, 2)->default(0.00);
            // $table->decimal('deduc_absent', 10, 2)->default(0.00);

            $table->decimal('gross_salary', 10, 2)->default(0.00);
            $table->decimal('total_deduction', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payslips');
    }
}
