<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockPointsColumnToCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('enium_points');
            $table->integer('stock_points');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->bigInteger('enium_points');
            $table->dropColumn('stock_points');
        });
    }
}
