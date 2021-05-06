<?php

namespace Database\Seeders;

use App\Models\UserEniumPointLevel;
use Illuminate\Database\Seeder;

class UserEniumPointLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserEniumPointLevel::factory([
            'level'            => 1,
            'point'            => 500,
            'monthly_residual' => 500
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 2,
            'point'            => 1000,
            'monthly_residual' => 1000
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 3,
            'point'            => 1500,
            'monthly_residual' => 1500
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 4,
            'point'            => 2000,
            'monthly_residual' => 2000
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 5,
            'point'            => 2500,
            'monthly_residual' => 2500
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 6,
            'point'            => 3000,
            'monthly_residual' => 3000
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 7,
            'point'            => 3500,
            'monthly_residual' => 3500
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 8,
            'point'            => 4000,
            'monthly_residual' => 4000
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 9,
            'point'            => 4500,
            'monthly_residual' => 4500
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 10,
            'point'            => 5000,
            'monthly_residual' => 5000
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 11,
            'point'            => 5500,
            'monthly_residual' => 5500
        ])->create();

        UserEniumPointLevel::factory([
            'level'            => 12,
            'point'            => 6000,
            'monthly_residual' => 6000
        ])->create();
    }
}
