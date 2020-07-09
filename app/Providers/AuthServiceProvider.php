<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
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
