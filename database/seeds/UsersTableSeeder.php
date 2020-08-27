<?php

use App\Models\Region;
use App\Models\User;
use App\Models\Office;
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
        for ($i=0; $i < 5; $i++) { 
            $this->createTestRegion();    
            $this->createTestOffice();    
        }
    }

    public function createDevsquadTeam()
    {
        $owner = factory(User::class)->create([
            'first_name' => 'DevSquad Master',
            'last_name'  => 'User',
            'email'      => 'team@devsquad.com',
            'role'       => 'Admin',
            'master'     => true,
        ]);

        $devsquad = factory(Region::class)->create([
            'region_manager_id' => $owner->id,
        ]);
        $devsquad->users()->attach($owner, ['role' => array_rand(User::TOPLEVEL_ROLES)]);

        $member = factory(User::class)->create([
            'first_name' => 'DevSquad',
            'last_name'  => 'User',
            'email'      => 'user@devsquad.com',
        ]);
        $devsquad->users()->attach($member, ['role' => array_rand(User::ROLES)]);

        for ($i = 0; $i < 50; $i++) {
            $member = factory(User::class)->create();
            $devsquad->users()->attach($member, ['role' => array_rand(User::ROLES)]);
        }

        for ($i = 0; $i < 50; $i++) {
            $master = factory(User::class)->create(['master' => true]);
            $devsquad->users()->attach($master, ['role' => array_rand(User::ROLES)]);
        }
    }

    public function createTestRegion()
    {
        $testOwner = factory(User::class)->create([
            'master' => false,
        ]);
    
        $testRegion = factory(Region::class)->create([
            'region_manager_id' => $testOwner->id,
        ]);
        $testRegion->users()->attach($testOwner, ['role' => array_rand(User::TOPLEVEL_ROLES)]);

        for ($i = 0; $i < 10; $i++) {
            $member = factory(User::class)->create();
            $testRegion->users()->attach($member, ['role' => array_rand(User::ROLES)]);
        }
    }

     public function createTestOffice()
    {
       
        $testOwner = factory(User::class)->create([
            'master' => false,
        ]);

        $testOfficeManager = factory(User::class)->create([
            'master' => false,
            'role' => 'Office Manager'
        ]);
        
        $region = factory(Region::class)->create([
            'region_manager_id' => $testOwner->id,
        ]);
    
        $testOffice = factory(Office::class)->create([
            'office_manager_id' => $testOfficeManager->id,
            'region_id'         => $region->id,
        ]);
        $testOffice->users()->attach($testOwner, ['role' => array_rand(User::TOPLEVEL_ROLES)]);

        for ($i = 0; $i < 10; $i++) {
            $member = factory(User::class)->create();
            $testOffice->users()->attach($member, ['role' => array_rand(User::ROLES)]);
        }
    }
}
