<?php

namespace Database\Factories;

use App\Models\Incentive;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncentivesFactory extends Factory
{
    protected $model = Incentive::class;

    public function definition()
    {
        $incentives = [
            ['number_installs' => 45,  'name' => 'Thailand',    'installs_needed' => 0,  'kw_needed' => 45],
            ['number_installs' => 50,  'name' => 'Plus 1',      'installs_needed' => 0,   'kw_needed' => 45],
            ['number_installs' => 65,  'name' => 'First Class',  'installs_needed' => 15,  'kw_needed' => 70],
            ['number_installs' => 80,  'name' => 'Model 3',      'installs_needed' => 20,  'kw_needed' => 90],
            ['number_installs' => 100, 'name' => 'Model S',      'installs_needed' => 25,  'kw_needed' => 100],
            ['number_installs' => 150, 'name' => 'Model X',      'installs_needed' => 30,  'kw_needed' => 120],
        ];

        $incentive = $this->faker->unique('name')->randomElement($incentives);

        return [
            'number_installs'   => $incentive['number_installs'],
            'name'              => $incentive['name'],
            'installs_needed'   => $incentive['installs_needed'],
            'kw_needed'         => $incentive['kw_needed'],
        ];
    }
}
