<?php

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Database\Seeder;

class TrainingContentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trainingSection = factory(TrainingPageSection::class)->create();
        $trainingContent = factory(TrainingPageContent::class)->create(['trainingPageSection_id' => $trainingSection->id]);
    }
}
