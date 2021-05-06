<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEniumPointsCalculationBasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enium_points_calculation_basis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('noble_pay_dealer_fee');
            $table->decimal('rep_residual', 3);
            $table->decimal('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enium_points_calculation_basis');
    }
}
