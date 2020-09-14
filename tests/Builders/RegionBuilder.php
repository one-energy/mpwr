<?php


namespace Tests\Builders;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class RegionBuilder
{
    use WithFaker;

    /** @var Region */
    public $region;

    public function __construct($attributes = [])
    {
        $this->faker = $this->makeFaker('en_US');
        $this->region  = (new Region)->forceFill(array_merge([
            'name' => Str::title($this->faker->word),
        ], $attributes));
    }

    public function save()
    {
        if (!$this->region->region_manager_id) {
            $this->region->region_manager_id = factory(User::class)->create()->id;
        }
        $this->region->save();

        return $this;
    }

    public function get()
    {
        return $this->region;
    }

    public function withManager(User $user)
    {
        $this->region->region_manager_id = $user->id;

        return $this;
    }

    // public function addMembers(int $qty)
    // {
    //     $users = factory(User::class, $qty)->create();

    //     foreach ($users as $user) {
    //         $this->region->users()->attach($user, ['role' => array_search('Setter', User::ROLES)]);
    //     }

    //     return $this;
    // }
}