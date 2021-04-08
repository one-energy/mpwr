<?php

namespace App\Facades\Actions;

use App\Actions\UpdateOrCreateNumberTracking as Action;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void execute(array $data)
 */
class UpdateOrCreateNumberTracking extends Facade
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
