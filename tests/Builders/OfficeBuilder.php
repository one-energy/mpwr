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

        $this->office->officeManager()->update([
            'office_id'     => $this->office->id,
            'department_id' => $this->office->region->department_id,
        ]);

        $this->office->region->regionManager()->update([
            'office_id' => $this->office->id,
        ]);

        return $this;
    }

    public function get(): Office
    {
        return $this->office;
    }

    public function withManager(?User $user = null): self
    {
        $user = $user ?? User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->office->office_manager_id = $user->id;

        return $this;
    }

    public function region(?Region $region = null): self
    {
        $region = $region ?? $this->createRegion();

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

    private function createRegion(): Region
    {
        return RegionBuilder::build()
            ->withManager()
            ->withDepartment()
            ->save()
            ->get();
    }
}
