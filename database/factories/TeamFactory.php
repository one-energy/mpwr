<?php


use App\Models\Team;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Storage;

$photos = collect([
    'batman.png',
    'captain-america.jpeg',
    'female-superhero.jpg',
    'hulk.jpg',
    'ironman.png',
    'mr-incredible.png',
    'robin.png',
    'superman.jpg',
    'superwoman.png',
    'wonderwoman.jpeg',
]);

/** @var Factory $factory */
$factory->define(Team::class, function (Faker $faker) use ($photos) {
    return [
        'name'      => $faker->name,
        'photo_url' => Storage::disk('public')->url('profiles/' . $photos->random()),
    ];
});
