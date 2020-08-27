<?php


namespace Tests\Builders;

use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class OfficeBuilder
{
    use WithFaker;

    /** @var Office */
    public $office;

    public function __construct($attributes = [])
    {
        $this->faker = $this->makeFaker('en_US');
        $this->office  = (new Office)->forceFill(array_merge([
            'name' => Str::title($this->faker->word),
        ], $attributes));
    }

    public function save()
    {
        if (!$this->office->office_manager_id) {
            $this->office->office_manager_id = factory(User::class)->create()->id;
        }
        $this->office->save();

        $this->office->users()->attach($this->office->owner, ['role' => array_search('Office Manager', User::TOPLEVEL_ROLES)]);

        return $this;
    }

    public function get()
    {
        return $this->office;
    }

    public function withManager(User $user)
    {
        $this->office->office_manager_id = $user->id;

        return $this;
    }

    public function region(Region $region)
    {
        $this->office->region_id = $region->id;

        return $this;
    }

    public function addMembers(int $qty)
    {
        $users = factory(User::class, $qty)->create();

        foreach ($users as $user) {
            $this->office->users()->attach($user, ['role' => array_search('Setter', User::ROLES)]);
        }

        return $this;
    }
}