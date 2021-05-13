<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersStockPointsTable extends Migration
{
    public function up()
    {
        Schema::create('customers_stock_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->integer('stock_recruiter')->nullable();
            $table->integer('stock_setting')->nullable();
            $table->integer('stock_personal_sale')->nullable();
            $table->integer('stock_pod_leader_team')->nullable();
            $table->integer('stock_manager')->nullable();
            $table->integer('stock_divisional')->nullable();
            $table->integer('stock_regional')->nullable();
            $table->integer('stock_department')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers_stock_points');
    }
}
