<?php

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class TrainingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $departments = Department::all();
        foreach ($departments as $department) {
            factory(TrainingPageSection::class)->create([
                'title' => 'Training Page',
                'department_id' => $department->id
            ]);
        }
        $inicialTrainingPages = TrainingPageSection::all();

        foreach ($inicialTrainingPages as $trainingPage) {
            factory(TrainingPageSection::class, rand(0,3))->create([
                'parent_id'     => $trainingPage->id,
                'department_id' => $trainingPage->department_id
            ]);
            $trueOrFalse = rand(0,1);
            if($trueOrFalse == 1){
                factory(TrainingPageContent::class, rand(0,3))->create([
                    'title'                    => $faker->name,
                    'description'              => $faker->text,
                    'training_page_section_id' => $trainingPage->id,
                    'video_url'                => 'https://youtu.be/cu9lJvjERPQ'
                ]);
            }
        }
    }

    public function getSections()
    {
        return TrainingPageSection::all();
    }
}
