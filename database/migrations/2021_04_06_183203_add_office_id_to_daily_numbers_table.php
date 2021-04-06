<?php

use App\Models\DailyNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfficeIdToDailyNumbersTable extends Migration
{
    public function up()
    {
        Schema::table('daily_numbers', function (Blueprint $table) {
            $table->foreignId('office_id')
                ->after('user_id')
                ->nullable()
                ->constrained();

            $table->index('office_id');
        });

        DailyNumber::with('user')->chunk(500, function ($dailyNumbers) {
            $dailyNumbers->each(function (DailyNumber $dailyNumber) {
                $dailyNumber->update(['office_id' => $dailyNumber->user->office_id]);
            });
        });
    }

    public function down()
    {
        Schema::table('daily_numbers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('office_id');
        });
    }
}
