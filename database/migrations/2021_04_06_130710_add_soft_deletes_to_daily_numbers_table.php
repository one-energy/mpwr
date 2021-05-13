<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToDailyNumbersTable extends Migration
{
    public function up()
    {
        Schema::table('daily_numbers', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('daily_numbers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
