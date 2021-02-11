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
            $table->foreignId('financing_id')->nullable()->after('id');
            $table->foreignId('financer_id')->nullable()->after('financing_id');
            $table->foreignId('term_id')->nullable()->after('financer_id');

            $table->bigInteger('enium_points')->default(0)->after('created_at');
            $table->decimal('sales_rep_comission')->default(0)->after('created_at');

            $table->foreign('financing_id')->references('id')->on('financings')->onDelete('cascade');
            $table->foreign('financer_id')->references('id')->on('financers')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
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
            $table->dropForeign(['financing_id']);
            $table->dropForeign(['financer_id']);
            $table->dropForeign(['term_id']);

            $table->dropColumn(['financing_id', 'financer_id', 'term_id', 'enium_points', 'sales_rep_comission']);
        });
    }
}
