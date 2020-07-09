<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('owner_id');
            $table->string('name');
            $table->text('photo_url')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
