<?php

namespace App\Facades\Actions;

use App\Actions\SpreadsheetDailyNumbers as Action;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void execute(array $dailyNumbers)
 */
class SpreadsheetDailyNumbers extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Action::class;
    }
}
