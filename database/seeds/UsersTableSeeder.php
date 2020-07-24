<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $photos = [
            'batman.png',
            'captain-america.jpeg',
            'female-superhero.jpg',
            'hulk.jpg',
            'ironman.png',
            'mr-incredible.png',
            'robin.png',
            'superman.jpg',
            'superwoman.png',
            'wonderwoman.jpeg',
        ];

        Storage::disk('public')->makeDirectory('profiles');

        foreach ($photos as $photo) {
            File::copy(
                __DIR__ . '/photos/' . $photo,
                storage_path('/app/public/profiles/' . $photo)
            );
        }

        $this->createDevsquadTeam();
        $this->createTestTeam();
    }

    public function createDevsquadTeam()
    {
        $owner = factory(User::class)->create([
            'first_name'   => 'DevSquad Master',
            'last_name'  => 'User',
            'email'  => 'team@devsquad.com',
            'master' => true,
        ]);

        $devsquad = factory(Team::class)->create([
            'owner_id' => $owner->id,
            'name'     => 'DevSquad',
        ]);
        $devsquad->users()->attach($owner, ['role' => User::OWNER]);

        $member = factory(User::class)->create([
            'first_name'  => 'DevSquad',
            'last_name' => 'User',
            'email' => 'user@devsquad.com',
        ]);
        $devsquad->users()->attach($member, ['role' => User::MEMBER]);

        for ($i = 0; $i < 50; $i++) {
            $member = factory(User::class)->create();
            $devsquad->users()->attach($member, ['role' => User::MEMBER]);
        }
        for ($i = 0; $i < 50; $i++) {
            $master = factory(User::class)->create(['master' => true]);
            $devsquad->users()->attach($master, ['role' => User::MEMBER]);
        }
    }

    public function createTestTeam()
    {
        $testOwner = factory(User::class)->create([
            'first_name'   => 'Test',
            'last_name' => 'User',
            'email'  => 'test@user.com',
            'master' => false,
        ]);

        $testTeam = factory(Team::class)->create([
            'owner_id' => $testOwner->id,
            'name'     => 'Test Team',
        ]);
        $testTeam->users()->attach($testOwner, ['role' => User::OWNER]);

        for ($i = 0; $i < 50; $i++) {
            $member = factory(User::class)->create();
            $testTeam->users()->attach($member, ['role' => User::MEMBER]);
        }
    }
}
