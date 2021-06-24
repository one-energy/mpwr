<?php

namespace Tests\Builders;

use App\Enum\Role;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class OfficeBuilder
{
    use WithFaker;

    public Office $office;

    public function __construct($attributes = [])
    {
        $this->faker  = $this->makeFaker('en_US');
        $this->office = (new Office)->forceFill(array_merge([
            'name' => Str::title($this->faker->word),
        ], $attributes));
    }

    public static function build($attributes = []): self
    {
        return new OfficeBuilder($attributes);
    }

    public function save(): self
    {
        if (!$this->office->office_manager_id) {
            $this->office->office_manager_id = User::factory()->create(['role' => Role::OFFICE_MANAGER])->id;
        }

        $this->office->save();

        return $this;
    }

    public function get(): Office
    {
        return $this->office;
    }

    public function withManager(User $user): self
    {
        $this->office->office_manager_id = $user->id;

        return $this;
    }

    public function region(Region $region): self
    {
        $this->office->region_id = $region->id;

        return $this;
    }

    public function addMembers(int $qty): self
    {
        $users = User::factory()->count($qty)->create();

        foreach ($users as $user) {
            $this->office->users()->attach($user, ['role' => Role::SETTER]);
        }

        return $this;
    }
}
