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

    /** @var Region */
    public Region $region;

    public function __construct($attributes = [])
    {
        $this->faker  = $this->makeFaker('en_US');
        $this->region = (new Region)->forceFill(array_merge([
            'name' => Str::title($this->faker->word),
        ], $attributes));
    }

    public static function build($attributes = [])
    {
        return new RegionBuilder($attributes);
    }

    public function withDepartment(Department $department)
    {
        $this->region->department_id = $department->id;

        return $this;
    }

    public function save()
    {
        if (!$this->region->region_manager_id) {
            $this->region->region_manager_id = User::factory()->create(['role' => Role::REGION_MANAGER])->id;
            $this->region->department_id     = Department::factory()->create()->id;
        }

        $this->region->save();

        return $this;
    }

    public function get(): Region
    {
        return $this->region;
    }

    public function withManager(User $user)
    {
        $this->region->region_manager_id = $user->id;

        return $this;
    }
}
