<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\AreaChart;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AreaChartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_set_period()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($john);

        Livewire::test(AreaChart::class)
            ->assertSet('period', 'w')
            ->call('setPeriod', 'm')
            ->assertSet('period', 'm')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_get_customers_where_panel_sold_from_current_week()
    {
        /** @var User $john */
        $john = User::factory()->create(['role' => 'Admin']);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => today(),
            'sales_rep_comission' => 2_000,
            'panel_sold'          => false
        ]);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => today(),
            'sales_rep_comission' => 2_000,
            'panel_sold'          => true
        ]);

        $this->actingAs($john);

        Livewire::test(AreaChart::class, ['period' => 'w', 'panelSold' => true])
            ->assertSet('data', [
                [
                    'date'       => today()->format('m-d-Y'),
                    'commission' => 2_000
                ]
            ])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_get_customers_where_panel_sold_from_current_month()
    {
        /** @var User $john */
        $john = User::factory()->create(['role' => 'Admin']);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => today()->addDay(),
            'sales_rep_comission' => 3_000,
            'panel_sold'          => true
        ]);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => today(),
            'sales_rep_comission' => 2_000,
            'panel_sold'          => true
        ]);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => today()->subMonth(),
            'sales_rep_comission' => 2_000,
            'panel_sold'          => true
        ]);

        $this->actingAs($john);

        Livewire::test(AreaChart::class, ['period' => 'm', 'panelSold' => true])
            ->assertSet('data', [
                [
                    'date'       => today()->format('m-d-Y'),
                    'commission' => 2_000
                ],
                [
                    'date'       => today()->addDay()->format('m-d-Y'),
                    'commission' => 3_000
                ],
            ])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_get_customers_where_panel_sold_from_current_year()
    {
        /** @var User $john */
        $john = User::factory()->create(['role' => 'Admin']);

        $firstDayOfThisYear = today()->startOfYear();
        $lastDayOfThisYear  = today()->endOfYear();

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => $firstDayOfThisYear,
            'sales_rep_comission' => 3_000,
            'panel_sold'          => true
        ]);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => $lastDayOfThisYear,
            'sales_rep_comission' => 2_000,
            'panel_sold'          => true
        ]);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => today()->addYear(),
            'sales_rep_comission' => 2_000,
            'panel_sold'          => true
        ]);

        $this->actingAs($john);

        Livewire::test(AreaChart::class, ['period' => 'y', 'panelSold' => true])
            ->assertSet('data', [
                [
                    'date'       => $firstDayOfThisYear->format('m-d-Y'),
                    'commission' => 3_000
                ],
                [
                    'date'       => $lastDayOfThisYear->format('m-d-Y'),
                    'commission' => 2_000
                ],
            ])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_get_all_customers_where_panel_sold()
    {
        /** @var User $john */
        $john = User::factory()->create(['role' => 'Admin']);

        $firstDayOfThisYear = today()->startOfYear();
        $lastDayOfThisYear  = today()->endOfYear();
        $threeYearsAgo      = today()->subYears(3);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => $firstDayOfThisYear,
            'sales_rep_comission' => 3_000,
            'panel_sold'          => true
        ]);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => $lastDayOfThisYear,
            'sales_rep_comission' => 2_000,
            'panel_sold'          => true
        ]);

        Customer::factory()->create([
            'is_active'           => true,
            'sales_rep_id'        => $john->id,
            'date_of_sale'        => $threeYearsAgo,
            'sales_rep_comission' => 4_000,
            'panel_sold'          => true
        ]);

        $this->actingAs($john);

        Livewire::test(AreaChart::class, ['period' => 'all', 'panelSold' => true])
            ->assertSet('data', [
                [
                    'date'       => $threeYearsAgo->format('m-d-Y'),
                    'commission' => 4_000
                ],
                [
                    'date'       => $firstDayOfThisYear->format('m-d-Y'),
                    'commission' => 3_000
                ],
                [
                    'date'       => $lastDayOfThisYear->format('m-d-Y'),
                    'commission' => 2_000
                ],
            ])
            ->assertHasNoErrors();
    }
}
