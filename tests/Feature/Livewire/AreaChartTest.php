<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;
use App\Http\Livewire\AreaChart;
use Carbon\Carbon;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AreaChartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    /** @test  */
    function area_chart_contains_livewire_component()
    {
        $this->get('/area-chart')->assertSeeLivewire('area-chart');
    }

    /** @test */
    public function it_should_draw_weekly_chart()
    {
        $period = 'w';
        $customers = factory(Customer::class, 10)->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])->create();
        factory(Customer::class, 50)->create();

        Livewire::test(AreaChart::class)
            ->set('period', $period)
            ->call('setPeriod');
    }
}