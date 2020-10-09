<?php

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
        $department  = factory(Department::class)->create();

        $userdptoOne = factory(User::class)->create([
            'first_name'    => 'Department one',
            'last_name'     => 'Manager',
            'email'         => 'onedmanager@devsquad.com',
            'role'          => 'Department Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);
        $department->department_manager_id = $userdptoOne->id;
        $department->save();
        
        //Regions
        factory(User::class)->create([
            'first_name'    => 'Region one',
            'last_name'     => 'Manager',
            'email'         => 'onermanager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Other Region one',
            'last_name'     => 'Manager',
            'email'         => 'otheronermanager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Region Manager',
            'master'        => true,
        ]);

        //Offices
        factory(User::class)->create([
            'first_name'    => 'Office one',
            'last_name'     => 'Manager',
            'email'         => 'oneomanager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);
        factory(User::class)->create([
            'first_name'    => 'Other Office one',
            'last_name'     => 'Manager',
            'email'         => 'otheroneomanager@devsquad.com',
            'department_id' => $department->id,
            'role'          => 'Office Manager',
            'master'        => true,
        ]);

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
    
        array_map(function ($item) use ($department, $officesName){
            $regionManager = factory(User::class)->create([
                'master'        => false,
                'role'          => 'Region Manager',
                'department_id' => $department->id
            ]);

            factory(Region::class)->create([
                'name'              => $item,
                'region_manager_id' => $regionManager->id,
                'department_id'     => $regionManager->department_id
            ]);
            array_map(function (){

            }, $officesName);
        }, $regionsName);
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
                    'department_id'     => $testOwner->department_id
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
