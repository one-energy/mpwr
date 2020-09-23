<?php

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class TrainingContentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $trainingSections      = TrainingPageSection::all();
        factory(TrainingPageContent::class)->create([
            'training_page_section_id' => $trainingSections[7]->id,
            'description'            => $faker->text,
        ]);
        factory(TrainingPageContent::class)->create([
            'training_page_section_id' => $trainingSections[6]->id,
            'description'            => $faker->text,
        ]);
        factory(TrainingPageContent::class)->create([
            'training_page_section_id' => $trainingSections[9]->id,
            'description'            => $faker->text,
        ]);
        factory(TrainingPageContent::class)->create([
            'training_page_section_id' => $trainingSections[10]->id,
            'description'            => $faker->text,
        ]);
    }
}
