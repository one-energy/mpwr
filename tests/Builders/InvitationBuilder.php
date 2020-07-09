<?php


namespace Tests\Builders;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;

class InvitationBuilder
{
    use WithFaker;

    private int $amount = 1;

    public Invitation $invitation;

    public function __construct($attributes = [])
    {
        $this->faker      = $this->makeFaker('en_US');
        $this->invitation = (new Invitation())->forceFill(array_merge([
            'email'   => $this->faker->email,
            'token'   => Uuid::uuid4()->toString(),
        ], $attributes));
    }

    public function save()
    {
        if ($this->amount == 1) {
            $this->invitation->save();

            return $this;
        }

        for ($i = 0; $i < $this->amount; $i++) {
            (new self($this->invitation->makeHidden('id')->toArray()))
                ->withEmail($this->faker->email)
                ->save();
        }
    }

    public function get()
    {
        return $this->invitation;
    }

    public function withEmail(string $email)
    {
        $this->invitation->email = $email;

        return $this;
    }

    public function make(int $qty)
    {
        $this->amount = $qty;

        return $this;
    }

    public function isAMaster()
    {
        $this->invitation->master = true;

        return $this;
    }

    public function for(User $user)
    {
        $this->invitation->user_id = $user->id;

        return $this;
    }
}
