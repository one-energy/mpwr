<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHoursWorkedHoursKnockedSatsAndCloserSitsToDailyNumbers extends Migration
{
    public function up()
    {
        Schema::table('daily_numbers', function (Blueprint $table) {
            $table->decimal('hours_worked', 4, 2)
                ->after('closes')
                ->default(0);
            $table->decimal('hours_knocked', 4, 2)
                ->after('hours_worked')
                ->default(0);
            $table->unsignedInteger('sats')
                ->after('hours_knocked')
                ->default(0);
            $table->unsignedInteger('closer_sits')
                ->after('sats')
                ->default(0);
        });
    }

    public function down()
    {
        Schema::table('daily_numbers', function (Blueprint $table) {
            $table->dropColumn(['hours_worked', 'hours_knocked', 'sats', 'closer_sits']);
        });
    }
}
