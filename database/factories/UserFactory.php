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
    $roles = $faker->randomElement(User::ROLES);
    $role = $roles['name'];

    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'          => bcrypt('secret'),
        'timezone'          => $faker->timezone,
        'department_id'     => null,
        'photo_url'         => Storage::disk('public')->url('profiles/' . 'profile.png'),
        'remember_token'    => Str::random(10),
        'role'              => $role,
        'pay'               => rand(10, 100)
    ];
});