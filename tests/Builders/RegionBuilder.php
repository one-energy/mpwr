<?php

namespace Tests\Builders;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class RegionBuilder
{
    use WithFaker;

    public bool $withManager = false;

    public Region $region;

    private User $user;

    public function __construct($attributes = [])
    {
        $this->faker  = $this->makeFaker('en_US');
        $this->region = (new Region)->forceFill(array_merge([
            'name' => Str::title($this->faker->word),
        ], $attributes));
    }

    public static function build($attributes = []): self
    {
        return new RegionBuilder($attributes);
    }

    public function withDepartment(?Department $department = null): self
    {
        $department = $department ?? $this->createDepartmentManager();

        $this->region->department_id = $department->id;

        return $this;
    }

    public function save(): self
    {
        $this->region->save();

        if (!$this->withManager) {
            $manager    = User::factory()->create(['role' => Role::REGION_MANAGER]);
            $department = Department::factory()->create();

            $manager->update(['department_id' => $department->id]);
            $this->region->update(['department_id' => $department->id]);
            $this->region->managers()->attach($manager->id);
        } else {
            $this->region->managers()->attach($this->user->id);
        }

        return $this;
    }

    public function get(): Region
    {
        return $this->region;
    }

    public function withManager(?User $user = null): self
    {
        $this->user        = $user;
        $this->withManager = true;

        return $this;
    }

    private function createDepartmentManager(): Department
    {
        $manager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        /** @var Department $department */
        $department = Department::factory()->create(['department_manager_id' => $manager->id]);

        $manager->update(['department_id' => $department->id]);

        return $department;
    }
}
