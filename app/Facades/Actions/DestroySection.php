<?php

namespace App\Facades\Actions;

use App\Actions\DestroySection as Action;
use App\Models\TrainingPageSection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void execute(TrainingPageSection $section)
 */
class DestroySection extends Facade
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
