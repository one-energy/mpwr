<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncentivesTable extends Migration
{
    public function up()
    {
        Schema::create('incentives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number_installs');
            $table->string('name');
            $table->integer('installs_achieved');
            $table->integer('installs_needed');
            $table->integer('kw_achieved');
            $table->integer('kw_needed');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incentives');
    }
}
