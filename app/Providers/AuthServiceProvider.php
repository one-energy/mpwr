<?php

namespace App\Providers;

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Policies\TrainingPageContentPolicy;
use App\Policies\TrainingsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        TrainingPageSection::class => TrainingsPolicy::class,
        TrainingPageContent::class => TrainingPageContentPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::guessPolicyNamesUsing(function ($modelClass) {
            $classDirname = str_replace('\Models', '',
                str_replace('/', '\\', dirname(str_replace('\\', '/', $modelClass)))
            );

            return [$classDirname . '\\Policies\\' . class_basename($modelClass) . 'Policy'];
        });
    }
}
