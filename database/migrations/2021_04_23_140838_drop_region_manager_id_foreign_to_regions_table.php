<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRegionManagerIdForeignToRegionsTable extends Migration
{
    public function up()
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropForeign('regions_region_manager_id_foreign');
        });
    }

    public function down()
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->foreign('region_manager_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
}
