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
        $trainingSection = factory(TrainingPageSection::class)->create();
    }
}
