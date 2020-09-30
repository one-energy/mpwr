<?php

use App\Models\Department;
use App\Models\Incentive;
use App\Models\User;
use Illuminate\Database\Seeder;

class IncentivesTableSeeder extends Seeder
{
    public function run()
    {
        $department      = Department::query()->first();
        $incentives = [
            ['number_installs' => 45,  'name' => 'Thailand',    'installs_achieved' => 110, 'installs_needed' => 0,  'kw_achieved' => 101, 'kw_needed' => 45, 'department_id' => $department->id],
            ['number_installs' => 50,  'name' => 'Plus 1',      'installs_achieved' => 100, 'installs_needed' => 0,  'kw_achieved' => 90,  'kw_needed' => 45, 'department_id' => $department->id],
            ['number_installs' => 65,  'name' => 'First Class', 'installs_achieved' => 90,  'installs_needed' => 15, 'kw_achieved' => 60,  'kw_needed' => 70, 'department_id' => $department->id],
            ['number_installs' => 80,  'name' => 'Model 3',     'installs_achieved' => 80,  'installs_needed' => 20, 'kw_achieved' => 40,  'kw_needed' => 90, 'department_id' => $department->id],
            ['number_installs' => 100, 'name' => 'Model S',     'installs_achieved' => 70,  'installs_needed' => 25, 'kw_achieved' => 30,  'kw_needed' => 100, 'department_id' => $department->id],
            ['number_installs' => 150, 'name' => 'Model X',     'installs_achieved' => 60,  'installs_needed' => 30, 'kw_achieved' => 20,  'kw_needed' => 120, 'department_id' => $department->id],
        ];
        
        Incentive::unguard();
        Incentive::query()->insert($incentives);
        Incentive::reguard();
    }
}
