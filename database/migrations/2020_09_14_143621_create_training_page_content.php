<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingPageContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_page_content', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('descriptrion');
            $table->unsignedBigInteger('trainingPageSection_id');
            $table->string('video_url');

            $table->foreign('trainingPageSection_id')->references('id')->on('training_page_section')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_page_content');
    }
}
