<?php

namespace Tests\Builders;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class TrainingSectionBuilder
{
    use WithFaker;

    public TrainingPageSection $section;

    public function __construct($attributes = [])
    {
        $this->faker   = $this->makeFaker('en_US');
        $this->section = (new TrainingPageSection)->forceFill(array_merge([
            'title' => Str::title($this->faker->name),
        ], $attributes));
    }

    public static function build(array $attributes = []): self
    {
        return new TrainingSectionBuilder($attributes);
    }

    public function save(): self
    {
        $this->section->save();

        return $this;
    }

    public function get(): TrainingPageSection
    {
        return $this->section;
    }

    public function withRegion(?Region $region = null)
    {
        $region = $region ?? $this->createRegion();

        $this->section->region_id = $region->id;

        return $this;
    }

    public function withDepartment(?Department $department = null)
    {
        if ($department === null) {
            $manager    = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
            $department = Department::factory()->create(['department_manager_id' => $manager->id]);
            $manager->update(['department_id' => $department->id]);
        }

        $this->section->department_id = $department->id;

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
