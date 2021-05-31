<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompensationTable extends Migration
{
    public function up()
    {
        Schema::create('compensation', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('benefit_id')->unsigned()->nullable();
            $table->foreign('benefit_id')->references('id')->on('benefits')->onDelete('set null');

            $table->double('from_amount');
            $table->double('to_amount');
            $table->float('employee_share');
            $table->float('employer_share');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('compensation');
    }
}
