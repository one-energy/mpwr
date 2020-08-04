<?php


use App\Models\Incentive;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Incentive::class, function (Faker $faker) {
    $incentives = [
        ['number_installs' => 45,  'name' => 'Thailand',    'installs_achieved' => 110, 'installs_needed' => 0,  'kw_achieved' => 101, 'kw_needed' => 45],
        ['number_installs' => 50,  'name' => 'Plus 1',      'installs_achieved' => 100, 'installs_needed' => 0,  'kw_achieved' => 90,  'kw_needed' => 45],
        ['number_installs' => 65,  'name' => 'First Class', 'installs_achieved' => 90,  'installs_needed' => 15, 'kw_achieved' => 60,  'kw_needed' => 70],
        ['number_installs' => 80,  'name' => 'Model 3',     'installs_achieved' => 80,  'installs_needed' => 20, 'kw_achieved' => 40,  'kw_needed' => 90],
        ['number_installs' => 100, 'name' => 'Model S',     'installs_achieved' => 70,  'installs_needed' => 25, 'kw_achieved' => 30,  'kw_needed' => 100],
        ['number_installs' => 150, 'name' => 'Model X',     'installs_achieved' => 60,  'installs_needed' => 30, 'kw_achieved' => 20,  'kw_needed' => 120],
    ];

    $incentive = $faker->unique('name')->randomElement($incentives);

    return [
        'number_installs'   => $incentive['number_installs'],
        'name'              => $incentive['name'],
        'installs_achieved' => $incentive['installs_achieved'],
        'installs_needed'   => $incentive['installs_needed'],
        'kw_achieved'       => $incentive['kw_achieved'],
        'kw_needed'         => $incentive['kw_needed'],
    ];
});
