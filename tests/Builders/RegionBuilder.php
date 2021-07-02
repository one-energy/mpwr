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

    public Region $region;

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
        if (!$this->region->region_manager_id) {
            $this->region->region_manager_id = User::factory()->create(['role' => Role::REGION_MANAGER])->id;
            $this->region->department_id     = Department::factory()->create()->id;
        }

        $this->region->save();

        $this->region->regionManager()->update([
           'department_id' => $this->region->department_id
        ]);

        return $this;
    }

    public function get(): Region
    {
        return $this->region;
    }

    public function withManager(?User $user = null): self
    {
        $user = $user ?? User::factory()->create(['role' => Role::REGION_MANAGER]);

        $this->region->region_manager_id = $user->id;

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
