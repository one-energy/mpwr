<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('sales_rep_recruiter_id')->nullable()->after('margin')->constrained('users');
            $table->integer('referral_override')->nullable()->after('sales_rep_recruiter_id');
            $table->foreignId('office_manager_id')->nullable()->after('referral_override')->constrained('users');
            $table->foreignId('region_manager_id')->nullable()->after('office_manager_id')->constrained('users');
            $table->foreignId('department_manager_id')->nullable()->after('region_manager_id')->constrained('users');
            $table->integer('office_manager_override')->nullable()->after('department_manager_id');
            $table->integer('region_manager_override')->nullable()->after('office_manager_override');
            $table->integer('department_manager_override')->nullable()->after('region_manager_override');
            $table->integer('misc_override_one')->nullable()->after('department_manager_override');
            $table->string('payee_one')->nullable()->after('misc_override_one');
            $table->string('note_one')->nullable()->after('payee_one');
            $table->integer('misc_override_two')->nullable()->after('note_one');
            $table->string('payee_two')->nullable()->after('misc_override_two');
            $table->string('note_two')->nullable()->after('payee_two');
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
            $table->dropForeign(['sales_rep_recruiter_id']);
            $table->dropColumn('sales_rep_recruiter_id');
            $table->dropColumn('referral_override');
            $table->dropForeign(['office_manager_id']);
            $table->dropColumn('office_manager_id');
            $table->dropForeign(['region_manager_id']);
            $table->dropColumn('region_manager_id');
            $table->dropForeign(['department_manager_id']);
            $table->dropColumn('department_manager_id');
            $table->dropColumn('office_manager_override');
            $table->dropColumn('region_manager_override');
            $table->dropColumn('department_manager_override');
            $table->dropColumn('misc_override_one');
            $table->dropColumn('payee_one');
            $table->dropColumn('note_one');
            $table->dropColumn('misc_override_two');
            $table->dropColumn('payee_two');
            $table->dropColumn('note_two');
        });
    }
}
