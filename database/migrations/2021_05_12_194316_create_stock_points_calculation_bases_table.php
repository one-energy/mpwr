<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockPointsCalculationBasesTable extends Migration
{
    public function up()
    {
        Schema::create('stock_points_calculation_bases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stock_base_point');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('stock_points_calculation_bases');
    }
}
