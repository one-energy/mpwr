<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCustomersEniumPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_customers_enium_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_sales_rep_id')->constrained('users');
            $table->foreignId('customer_id')->constrained();
            $table->integer('enium_points_of_sale');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_customers_enium_points');
    }
}
