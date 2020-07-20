<?php


use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
$factory->define(User::class, function (Faker $faker) use ($photos) {
    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'          => bcrypt('secret'),
        'timezone'          => $faker->timezone,
        'photo_url'         => Storage::disk('public')->url('profiles/' . $photos->random()),
        'remember_token'    => Str::random(10),
        'role'              => User::MEMBER,
        'office'            => $faker->city,
        'pay'               => rand(10, 100)
    ];
});
