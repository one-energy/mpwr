<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrainingTypeToSectionFilesTable extends Migration
{
    public function up()
    {
        Schema::table('section_files', function (Blueprint $table) {
            $table->string('training_type', 100)
                ->default('files')
                ->after('path');
        });
    }

    public function down()
    {
        Schema::table('section_files', function (Blueprint $table) {
            $table->dropColumn('training_type');
        });
    }
}
