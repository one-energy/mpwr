<?php

use App\Models\Financer;
use Illuminate\Database\Seeder;

class FinancersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Financer::class)->create(
            [
                'id' => 1,
                'name' => 'Enium'
            ]
        );

        factory(Financer::class)->create(
            [
                'id' => 2,
                'name' => 'Other'
            ]
        );
    }
}
