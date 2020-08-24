<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_user', function (Blueprint $table) {
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role');

            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_user');
    }
}
