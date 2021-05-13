<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomersStockPointsTable extends Migration
{
    public function up()
    {
        Schema::create('customers_stock_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->integer('stock_recruiter');
            $table->integer('stock_setting');
            $table->integer('stock_personal_sale');
            $table->integer('stock_pod_leader_team');
            $table->integer('stock_manager');
            $table->integer('stock_divisional');
            $table->integer('stock_regional');
            $table->integer('stock_department');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_customers_stock_points');
    }
}
