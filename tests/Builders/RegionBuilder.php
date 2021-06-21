<?php


namespace Tests\Builders;

use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class RegionBuilder
{
    use WithFaker;

    /** @var Region */
    public $region;

    public ?User $manager = null;

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

    public function withDepartment($department)
    {
        $this->region->department_id = $department->id;
        return $this;
    }

    public function save()
    {
        if ($this->manager === null) {
            $this->manager               = User::factory()->create();
            $this->region->department_id = Department::factory()->create()->id;
        }
        $this->region->save();
        $this->region->managers()->attach($this->manager->id);

        return $this;
    }

    public function get()
    {
        return $this->region;
    }

    public function withManager(User $user)
    {
        $this->manager = $user;

        return $this;
    }
}
