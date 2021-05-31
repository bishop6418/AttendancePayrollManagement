<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{

    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('rate_type');
            $table->bigInteger('basic_salary');
            $table->bigInteger('additional');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
