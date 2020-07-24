<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTeamsToRegions extends Migration
{
    
    public function up()
    {
        Schema::rename('teams', 'regions');

        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn('photo_url');
        });
    }

    public function down()
    {
        Schema::rename('regions', 'teams');

        Schema::table('teams', function (Blueprint $table) {
            $table->string('photo_url')->after('name');
        });
    }
}
