<?php

namespace Database\Seeders;

use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentOne extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department  = Department::factory()->create();

        $userDepartmentOne = User::factory()->create([
            'first_name'    => 'Department one',
            'last_name'     => 'Manager',
            'email'         => '1.departmentmanager@devsquad.com',
            'role'          => 'Department Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);
        $department->department_manager_id = $userDepartmentOne->id;
        $department->save();

        //Region
        User::factory()->create([
            'first_name'    => 'Region one',
            'last_name'     => 'Manager',
            'email'         => '1.regionmanager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);
        User::factory()->create([
            'first_name'    => 'Other Region one',
            'last_name'     => 'Manager',
            'email'         => '1.region2manager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);

        //Office
        User::factory()->create([
            'first_name'    => 'Office one',
            'last_name'     => 'Manager',
            'email'         => '1.officemanager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);
        User::factory()->create([
            'first_name'    => 'Other Office one',
            'last_name'     => 'Manager',
            'email'         => '1office2manager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);

        $officesName = array(
            'Palmdale',
            'Victorville',

        );

        $regionsName = array(
            'South',
            'North',
        );

        array_map(function ($region) use ($department, $officesName){
            $regionManager = User::factory()->create([
                'master'        => false,
                'role'          => 'Region Manager',
                'department_id' => $department->id
            ]);

            $region = factory(Region::class)->create([
                'name'              => $region,
                'region_manager_id' => $regionManager->id,
                'department_id'     => $regionManager->department_id
            ]);
            array_map(function ($office) use ($department, $region){
                $testOfficeManager = User::factory()->create([
                    'master'        => false,
                    'role'          => 'Office Manager',
                    'department_id' => $department
                ]);
                $office = factory(Office::class)->create([
                    'name'              => $office,
                    'office_manager_id' => $testOfficeManager->id,
                    'region_id'         => $region->id,
                ]);

                for ($x = 0; $x < 10; $x++) {
                    $member = User::factory()->create([
                        'role'          => ($x & 1) ? "Setter" : "Sales rep",
                        'office_id'     => $office->id,
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
            }, $officesName);
        }, $regionsName);
    }
}
