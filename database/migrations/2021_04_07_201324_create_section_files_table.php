<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionFilesTable extends Migration
{
    public function up()
    {
        Schema::create('section_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_page_section_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('original_name');
            $table->string('type');
            $table->integer('size');
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_files');
    }
}
