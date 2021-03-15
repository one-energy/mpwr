<?php

namespace Database\Seeders;

use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentTwo extends Seeder
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
            'first_name'    => 'Department two',
            'last_name'     => 'Manager',
            'email'         => '2.departmentmanager@devsquad.com',
            'role'          => 'Department Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);
        $department->department_manager_id = $userDepartmentOne->id;
        $department->save();

        //Region
        User::factory()->create([
            'first_name'    => 'Region two',
            'last_name'     => 'Manager',
            'email'         => '2.regionmanager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);
        User::factory()->create([
            'first_name'    => 'Other Region two',
            'last_name'     => 'Manager',
            'email'         => '2.region2manager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);

        //Office
        User::factory()->create([
            'first_name'    => 'Office two',
            'last_name'     => 'Manager',
            'email'         => '2.officemanager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);
        User::factory()->create([
            'first_name'    => 'Other Office two',
            'last_name'     => 'Manager',
            'email'         => '2.office2manager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);

        $officesName = array(
            'NY',
            'Miami',
        );

        $regionsName = array(
            'East',
            'West',
        );

        array_map(function ($region) use ($department, $officesName){
            $regionManager = User::factory()->create([
                'master'        => false,
                'role'          => 'Region Manager',
                'department_id' => $department->id
            ]);

            $region = Region::factory()->create([
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
                $office = Office::factory()->create([
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
                        DailyNumber::factory()->create([
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
