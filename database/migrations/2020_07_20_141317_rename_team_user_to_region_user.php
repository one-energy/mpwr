<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTeamUserToRegionUser extends Migration
{
    public function up()
    {
        Schema::rename('team_user', 'region_user');
        Schema::table('team_user', function (Blueprint $table) {
            $table->renameColumn('region_id', 'team_id');
            $table->foreign('team_id')->references('id')->on('regions');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::rename('region_user', 'team_user');
    }
}
