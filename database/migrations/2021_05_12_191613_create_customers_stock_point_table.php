<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersStockPointTable extends Migration
{
    public function up()
    {
        Schema::create('customers_stock_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->unique();
            $table->unsignedInteger('stock_recruiter')->nullable();
            $table->unsignedInteger('stock_setting')->nullable();
            $table->unsignedInteger('stock_personal_sale')->nullable();
            $table->unsignedInteger('stock_pod_leader_team')->nullable();
            $table->unsignedInteger('stock_manager')->nullable();
            $table->unsignedInteger('stock_divisional')->nullable();
            $table->unsignedInteger('stock_regional')->nullable();
            $table->unsignedInteger('stock_department')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers_stock_points');
    }
}
