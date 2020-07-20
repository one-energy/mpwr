<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameRegionUserFields extends Migration
{
    public function up()
    {
        Schema::table('region_user', function (Blueprint $table) {
            $table->renameColumn('team_id', 'region_id');
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    public function down()
    {
        //
    }
}
