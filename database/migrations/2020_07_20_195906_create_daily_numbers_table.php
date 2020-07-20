<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyNumbersTable extends Migration
{
    public function up()
    {
        Schema::create('daily_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->integer('doors');
            $table->decimal('hours', 2, 2)->nullable();
            $table->integer('sets');
            $table->integer('sits');
            $table->integer('set_closes');
            $table->integer('closes');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_numbers');
    }
}
