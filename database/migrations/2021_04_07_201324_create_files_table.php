<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_page_section_id')->cascade();
            $table->string('name');
            $table->string('original_name');
            $table->string('type');
            $table->integer('size');
            $table->string('path');
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
        Schema::dropIfExists('section_files');
    }
}
