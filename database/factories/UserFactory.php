<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    private $departmentId;

    public function definition()
    {
        $roles = $this->faker->randomElement(User::ROLES);
        $role  = $roles['name'];


        return [
            'first_name'                  => $this->faker->firstName,
            'last_name'                   => $this->faker->lastName,
            'email'                       => $this->faker->unique()->safeEmail,
            'email_verified_at'           => now(),
            'password'                    => bcrypt('secret'),
            'timezone'                    => $this->faker->timezone,
            'department_id'               => null,
            'photo_url'                   => Storage::disk('public')->url('profiles/' . 'profile.png'),
            'remember_token'              => Str::random(10),
            'role'                        => $role,
            'pay'                         => rand(10, 100),
            'department_manager_override' => 10,
            'region_manager_override'     => 10,
            'office_manager_override'     => 10,
        ];
    }

    function configure()
    {
        return $this->afterCreating(function (User $user) {
            $this->departmentId = $user->department_id;
        });
    }
}
