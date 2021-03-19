<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentDevsquadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department  = Department::factory()->create(['name' => 'Devsquad Department']);

        $userDepartmentOne = User::factory()->create([
            'first_name'    => 'Devsquad',
            'last_name'     => 'Department Manager',
            'email'         => 'devsquadmanager@devsquad.com',
            'role'          => 'Department Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $department->department_manager_id = $userDepartmentOne->id;
        $department->save();

        TrainingPageSection::factory()->create([
            'title' => 'Training Page',
            'department_id' => $department->id
        ]);
    }
}
