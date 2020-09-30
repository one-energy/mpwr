<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentIdToIncentivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incentives', function (Blueprint $table) {
            $table->foreignId('department_id')->references('id')->on('departments')->delete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incentives', function (Blueprint $table) {
            if (env('DB_CONNECTION') !== 'sqlite') {
                $table->dropForeign(['department_id']);
            }
        });
    }
}
