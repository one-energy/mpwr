<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    protected $model = Invitation::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'token'   => Str::uuid(),
            'email'   => $this->faker->unique()->email,
            'master'  => Arr::random([true, false])
        ];
    }
}
