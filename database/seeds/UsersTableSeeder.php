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

        // $this->createDevsquadTeam();
        $this->createExampleValues();
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
    }

    public function createTestRegion()
    {
        $testOwner = factory(User::class)->create([
            'master' => false,
            'role' => 'Region Manager'
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
            'role' => 'Region Manager'
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

    public function createExampleValues()
    {
        $officesName = array(
            'Palmdale',
            'Victorville',
            'San Bernardino',
            'Stockton',
            'Fairfield',
            'Vacaville',
            'Cordella',
            'Vallejo',
            'Fresno',
            'Pittsburg',
            'LA',
            'Riverside'
        );

        $regionsName = array(
            'west',
            'north',
            'south',
            'east',
        );

        $regionKey = 0;
        for ($i = 0; $i < 12; $i++) {

            if ($i == 0 || $i == 3 || $i == 6 || $i == 10) {
                $testOwner = factory(User::class)->create([
                    'master' => false,
                    'role' => 'Region Manager'
                ]);
                $region = factory(Region::class)->create([
                    'name' => $regionsName[$regionKey],
                    'region_manager_id' => $testOwner->id
                ]);
                $region->users()->attach($testOwner, ['role' => array_rand(User::TOPLEVEL_ROLES)]);
                if (($regionKey + 1) < 4) {
                    $regionKey++;
                }
            }

            $testOfficeManager = factory(User::class)->create([
                'master' => false,
                'role' => 'Office Manager'
            ]);

            print_r($regionKey);
            $testOffice = factory(Office::class)->create([
                'name' => $officesName[$i],
                'office_manager_id' => $testOfficeManager->id,
                'region_id'         => $region->id,
            ]);
            $testOffice->users()->attach($testOwner, ['role' => array_rand(User::TOPLEVEL_ROLES)]);
            for ($x = 0; $x < 10; $x++) {
                $member = factory(User::class)->create();
                $testOffice->users()->attach($member, ['role' => array_rand(User::ROLES)]);
            }
        }
    }
}
