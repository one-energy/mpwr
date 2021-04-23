<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOfficeManagerIdForeignToOfficesTable extends Migration
{
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropForeign('offices_office_manager_id_foreign');
        });
    }

    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->foreign('office_manager_id')
                ->references('id')
                ->on('users');
        });
    }
}
