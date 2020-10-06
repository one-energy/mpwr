<?php

use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use App\Models\Office;
use App\Models\TrainingPageSection;
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
        $this->createExampleValues();
    }

    public function createDevsquadTeam()
    {
        $departmentOne  = factory(Department::class)->create();
        $departmentTwo  = factory(Department::class)->create();

        factory(User::class)->create([
            'first_name'    => 'DevSquad Master',
            'last_name'     => 'User',
            'email'         => 'team@devsquad.com',
            'role'          => 'Owner',
            'department_id' => $departmentOne->id,
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Admin',
            'last_name'     => 'Devsquad',
            'email'         => 'admin@devsquad.com',
            'role'          => 'Admin',
            'department_id' => $departmentOne->id,
            'master'        => true,
        ]);
        
        $userdptoOne = factory(User::class)->create([
            'first_name'    => 'Department one',
            'last_name'     => 'Manager',
            'email'         => 'onedmanager@devsquad.com',
            'role'          => 'Department Manager',
            'department_id' => $departmentOne->id,
            'master'        => true,
        ]);
        $departmentOne->department_manager_id = $userdptoOne->id;
        $departmentOne->save();

        $userdptoTwo = factory(User::class)->create([
            'first_name'    => 'Department Two',
            'last_name'     => 'Manager',
            'email'         => 'twodmanager@devsquad.com',
            'department_id' => $departmentTwo->id,
            'role'          => 'Department Manager',
            'master'        => true,
            ]);
        $departmentTwo->department_manager_id = $userdptoTwo->id;
        $departmentTwo->save();
        
        factory(User::class)->create([
            'first_name'    => 'Region one',
            'last_name'     => 'Manager',
            'email'         => 'onermanager@devsquad.com',
            'department_id' => $departmentOne->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Other Region one',
            'last_name'     => 'Manager',
            'email'         => 'otheronermanager@devsquad.com',
            'department_id' => $departmentOne->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Region two',
            'last_name'     => 'Manager',
            'email'         => 'twormanager@devsquad.com',
            'department_id' => $departmentTwo->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Other Region two',
            'last_name'     => 'Manager',
            'email'         => 'othertwormanager@devsquad.com',
            'department_id' => $departmentTwo->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Office one',
            'last_name'     => 'Manager',
            'email'         => 'oneomanager@devsquad.com',
            'department_id' => $departmentOne->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Other Office one',
            'last_name'     => 'Manager',
            'email'         => 'otheroneomanager@devsquad.com',
            'department_id' => $departmentOne->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Office two',
            'last_name'     => 'Manager',
            'email'         => 'twoomanager@devsquad.com',
            'department_id' => $departmentTwo->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Other Office two',
            'last_name'     => 'Manager',
            'email'         => 'othertwoomanager@devsquad.com',
            'department_id' => $departmentTwo->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);


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
            'West',
            'North',
            'South',
            'East',
        );

        $regionKey = 0;

        $department = factory(Department::class)->create();

        $userAdmin = factory(User::class)->create([
            'master'        => false,
            'role'          => 'Department Manager',
            'department_id' => $department->id
        ]);

        $department->department_manager_id = $userAdmin->id;
        $department->save();
        for ($i = 0; $i < 12; $i++) {

            if ($i == 0 || $i == 3 || $i == 6 || $i == 10) {
                $testOwner = factory(User::class)->create([
                    'master'        => false,
                    'role'          => 'Region Manager',
                    'department_id' => $department->id
                ]);
                $region = factory(Region::class)->create([
                    'name'              => $regionsName[$regionKey],
                    'region_manager_id' => $testOwner->id,
                    'department_id'     => $department
                ]);
                if (($regionKey + 1) < 4) {
                    $regionKey++;
                }
            }

            $testOfficeManager = factory(User::class)->create([
                'master'        => false,
                'role'          => 'Office Manager',
                'department_id' => $department
            ]);

            $testOffice = factory(Office::class)->create([
                'name'              => $officesName[$i],
                'office_manager_id' => $testOfficeManager->id,
                'region_id'         => $region->id,
            ]);
            
            for ($x = 0; $x < 10; $x++) {
                $member = factory(User::class)->create([
                    'office_id'     => $testOffice->id,
                    'department_id' => $department
                ]);
                $today  = date('d');
                $date   = date('Y-m-01');
                for($y = 0; $y < ($today - 1); $y++){
                    factory(DailyNumber::class)->create([
                        'date'    => date('Y-m-d', strtotime($date . '+' . $y . 'day')),
                        'user_id' => $member->id,
                        'hours'   => rand(0,24)
                    ]);
                }
            }
        }
    }
}
