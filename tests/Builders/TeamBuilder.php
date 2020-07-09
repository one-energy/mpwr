<?php


namespace Tests\Builders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class TeamBuilder
{
    use WithFaker;

    /** @var Team */
    public $team;

    public function __construct($attributes = [])
    {
        $this->faker = $this->makeFaker('en_US');
        $this->team  = (new Team)->forceFill(array_merge([
            'name' => Str::title($this->faker->word),
        ], $attributes));
    }

    public function save()
    {
        if (!$this->team->owner_id) {
            $this->team->owner_id = factory(User::class)->create()->id;
        }

        $this->team->save();

        $this->team->users()->attach($this->team->owner, ['role' => User::OWNER]);

        return $this;
    }

    public function get()
    {
        return $this->team;
    }

    public function withOwner(User $user)
    {
        $this->team->owner_id = $user->id;

        return $this;
    }

    public function addMembers(int $qty)
    {
        $users = factory(User::class, $qty)->create();

        foreach ($users as $user) {
            $this->team->users()->attach($user, ['role' => User::MEMBER]);
        }

        return $this;
    }

}
