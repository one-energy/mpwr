<?php

use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class TrainingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departmentOne = Department::find(1);
        $firstSectionDeptOne = factory(TrainingPageSection::class)->create([
            'title' => 'Training Page',
            'department_id' => $departmentOne->id
        ]);
        $trainingSectionsDeptTwo = factory(TrainingPageSection::class, 10)->create([
            'department_id' => $departmentOne->id
        ]);
        $trainingSectionsDeptTwo->map(function($section, $key) use($firstSectionDeptOne) {
            if($key > 4){
                $section->parent_id = rand(1,5);
                $section->save();
            }else{
                $section->parent_id = $firstSectionDeptOne->id;
                $section->save();
            }
        });

        $departmentManager = User::query()->whereRole("Department Manager")->first();
        $department = factory(Department::class)->create(["department_manager_id" => $departmentManager]);
        $firstSection = factory(TrainingPageSection::class)->create([
            'title' => 'Training Page',
            'department_id' => $department->id
        ]);
        $trainingSections = factory(TrainingPageSection::class, 10)->create([
            'department_id' => $department->id
        ]);

        $trainingSections->map(function($section, $key) use($firstSection) {
            if($key > 4){
                $section->parent_id = rand(13,18);
                $section->save();
            }else{
                $section->parent_id = $firstSection->id;
                $section->save();
            }
        });
    }

    public function getSections()
    {
        return TrainingPageSection::all();
    }
}
