<?php

use App\Models\TrainingPageSection;
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
        $firstSection = factory(TrainingPageSection::class)->create(['title' => 'Training Page']);
        $trainingSections = factory(TrainingPageSection::class, 10)->create();

        $trainingSections->map(function($section, $key) use($firstSection) {
            if($key > 4){
                $section->parent_id = rand(1,5);
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
