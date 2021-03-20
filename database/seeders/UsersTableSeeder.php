<?php

namespace Database\Seeders;

use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\Region;
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
                __DIR__ . '/photos/' . 'profile.png',
                storage_path('/app/public/profiles/' . 'profile.png')
            );
        }

        $this->createDevsquadTeam();
    }

    public function createDevsquadTeam()
    {
        User::factory()->create([
            'first_name'    => 'Admin',
            'last_name'     => 'Devsquad',
            'email'         => 'admin@devsquad.com',
            'role'          => 'Admin',
            'department_id' => null,
            'master'        => true,
        ]);
    }

    public function createTestRegion()
    {
        $testOwner = User::factory()->create([
            'master' => false,
            'role'   => 'Region Manager',
        ]);

        Region::factory()->create([
            'region_manager_id' => $testOwner->id,
        ]);
    }

    public function createTestOffice()
    {

        $testOwner = User::factory()->create([
            'master' => false,
            'role'   => 'Region Manager',
        ]);

        $testOfficeManager = User::factory()->create([
            'master' => false,
            'role'   => 'Office Manager',
        ]);

        $region = Region::factory()->create([
            'region_manager_id' => $testOwner->id,
        ]);

        $testOffice = Office::factory()->create([
            'office_manager_id' => $testOfficeManager->id,
            'region_id'         => $region->id,
        ]);
        $testOffice->users()->attach($testOwner, ['role' => User::array_rand()]);

        for ($i = 0; $i < 10; $i++) {
            $member = User::factory()->create();
            $testOffice->users()->attach($member, ['role' => User::array_rand()]);
        }
    }

    public function createExampleDepartmentOne($department)
    {
        $officesName = [
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
            'Riverside',
        ];

        $regionsName = [
            'West',
            'North',
            'South',
            'East',
        ];

        $regionKey = 0;

        for ($i = 0; $i < 12; $i++) {

            if ($i == 0 || $i == 3 || $i == 6 || $i == 10) {
                $testOwner = User::factory()->create([
                    'master'        => false,
                    'role'          => 'Region Manager',
                    'department_id' => $department->id,
                ]);
                $region    = Region::factory()->create([
                    'name'              => $regionsName[$regionKey],
                    'region_manager_id' => $testOwner->id,
                    'department_id'     => $testOwner->department_id,
                ]);
                if (($regionKey + 1) < 4) {
                    $regionKey++;
                }
            }

            $testOfficeManager = User::factory()->create([
                'master'        => false,
                'role'          => 'Office Manager',
                'department_id' => $department,
            ]);

            $testOffice = Office::factory()->create([
                'name'              => $officesName[$i],
                'office_manager_id' => $testOfficeManager->id,
                'region_id'         => $region->id,
            ]);

            for ($x = 0; $x < 10; $x++) {
                $member = User::factory()->create([
                    'office_id'     => $testOffice->id,
                    'department_id' => $department,
                ]);
                $today  = date('d');
                $date   = date('Y-m-01');
                for ($y = 0; $y < ($today - 1); $y++) {
                    DailyNumber::factory()->create([
                        'date'    => date('Y-m-d', strtotime($date . '+' . $y . 'day')),
                        'user_id' => $member->id,
                        'hours'   => rand(0, 24),
                    ]);
                }
            }
        }
    }

    public function createExampleDepartmentTwo($department)
    {
        $officesName = [
            'NY',
            'Boston',
            'Chicago',
            'San Diego',
            'Orlando',
            'Filadélfia',
            'Nova Orleans',
            'Detroit',
            'Atlanta',
            'San Jose',
            'Oakland',
            'Dallas',
        ];

        $regionsName = [
            'Southeast',
            'Northeast',
            'Southwest',
            'Eastwest',
        ];

        $regionKey = 0;

        for ($i = 0; $i < 12; $i++) {

            if ($i == 0 || $i == 3 || $i == 6 || $i == 10) {
                $testOwner = User::factory()->create([
                    'master'        => false,
                    'role'          => 'Region Manager',
                    'department_id' => $department->id,
                ]);
                $region    = Region::factory()->create([
                    'name'              => $regionsName[$regionKey],
                    'region_manager_id' => $testOwner->id,
                    'department_id'     => $testOwner->department_id,
                ]);
                if (($regionKey + 1) < 4) {
                    $regionKey++;
                }
            }

            $testOfficeManager = User::factory()->create([
                'master'        => false,
                'role'          => 'Office Manager',
                'department_id' => $department,
            ]);

            $testOffice = Office::factory()->create([
                'name'              => $officesName[$i],
                'office_manager_id' => $testOfficeManager->id,
                'region_id'         => $region->id,
            ]);

            for ($x = 0; $x < 10; $x++) {
                $member = User::factory()->create([
                    'office_id'     => $testOffice->id,
                    'department_id' => $department,
                ]);
                $today  = date('d');
                $date   = date('Y-m-01');
                for ($y = 0; $y < ($today - 1); $y++) {
                    DailyNumber::factory()->create([
                        'date'    => date('Y-m-d', strtotime($date . '+' . $y . 'day')),
                        'user_id' => $member->id,
                        'hours'   => rand(0, 24),
                    ]);
                }
            }
        }
    }
}
