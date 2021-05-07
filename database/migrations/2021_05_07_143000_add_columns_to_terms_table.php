<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->float('noble_pay_dealer_fee', places:3)->after('value');
            $table->float('rep_residual', places:3)->after('noble_pay_dealer_fee');
            $table->decimal('amount')->after('rep_residual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->dropColumn('noble_pay_dealer_fee');
            $table->dropColumn('rep_residual');
            $table->dropColumn('amount');
        });
    }
}
