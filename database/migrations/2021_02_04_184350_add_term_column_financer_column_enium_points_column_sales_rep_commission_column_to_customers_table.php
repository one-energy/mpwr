<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTermColumnFinancerColumnEniumPointsColumnSalesRepCommissionColumnToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('financing_id')->constrained()->onDelete('cascade');
            $table->foreignId('financer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('term_id')->nullable()->constrained()->onDelete('cascade');
            $table->bigInteger('enium_points');
            $table->bigInteger('sales_rep_comission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('financing_id');
            $table->dropColumn('financer_id');
            $table->dropColumn('term_id');
            $table->dropColumn('enium_points');
            $table->dropColumn('sales_rep_comission');
        });
    }
}
