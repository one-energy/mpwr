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
            $table->integer('doors')->nullable();
            $table->decimal('hours', 4, 2)->nullable();
            $table->integer('sets')->nullable();
            $table->integer('sits')->nullable();
            $table->integer('set_closes')->nullable();
            $table->integer('closes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_numbers');
    }
}
