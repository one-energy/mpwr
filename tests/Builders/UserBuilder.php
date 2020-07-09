<?php


namespace Tests\Builders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;

class UserBuilder
{
    use WithFaker;

    public User $user;

    private int $amount = 1;

    private Collection $items;

    private ?Team $team = null;

    public function __construct($attributes = [])
    {
        $this->faker = $this->makeFaker('en_US');
        $this->user  = (new User)->forceFill(array_merge([
            'name'              => $this->faker->name,
            'email'             => $this->faker->email,
            'password'          => bcrypt('secret'),
            'email_verified_at' => now(),
        ], $attributes));
        $this->items = collect([]);
    }

    public function save()
    {
        if ($this->amount == 1) {
            $this->user->save();

            if ($this->team) {
                $this->team->owner_id = $this->user->id;
                $this->team->save();

                $this->team->users()->attach($this->user->id, ['role' => User::OWNER]);
            }
        }

        if ($this->amount > 1) {
            for ($i = 0; $i < $this->amount; $i++) {
                $user = (new self($this->user->makeHidden('id')->toArray()))
                    ->withEmail($this->faker->email)
                    ->withName($this->faker->name)
                    ->save()->get();

                if ($this->team) {
                    $this->team->users()->attach($user, ['role' => $i == 0 ? User::OWNER : User::MEMBER]);
                }

                $this->items->push($user);
            }
        }

        return $this;
    }

    /**
     * @return User|Collection
     */
    public function get()
    {
        if ($this->items->count() > 0) {
            return $this->items;
        }

        return $this->user;
    }

    public function withEmail(string $email)
    {
        $this->user->email = $email;

        return $this;
    }

    public function withPassword(string $password = null)
    {
        if (!$password) {
            $password = 'secret';
        }

        $this->user->password = bcrypt($password);

        return $this;
    }

    public function withTimezone(string $timezone = null)
    {
        if (!$timezone) {
            $timezone = $this->faker->timezone;
        }

        $this->user->timezone = $timezone;

        return $this;
    }

    public function emailVerified()
    {
        $this->user->email_verified_at = now();

        return $this;
    }

    public function emailUnverified()
    {
        $this->user->email_verified_at = null;

        return $this;
    }

    public function asMaster()
    {
        $this->user->master = true;

        return $this;
    }

    public function make(int $qty)
    {
        $this->amount = $qty;

        return $this;
    }

    public function withName(string $name)
    {
        $this->user->name = $name;

        return $this;
    }

    public function withATeam()
    {
        $this->team = factory(Team::class)->make();

        return $this;
    }
}
