<?php

namespace Tests\Feature\Profile;

use App\Models\Customer;
use App\Models\CustomersStockPoint;
use App\Models\MultiplierOfYear;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class StockPointsTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public Customer $customer;
    public float $multiplier;
    public Collection $userPersonalStockPoints;
    public Collection $teamStockPoints;

    protected function setUp ():void
    {
        parent::setUp();

        $this->userPersonalStockPoints = collect([]);
        $this->teamStockPoints = collect([]);

        $this->user = User::factory()->create(['role' => 'Sales Rep']);
        $this->actingAs($this->user);

        $this->multiplier = MultiplierOfYear::factory()->create(['year' => 2021])->multiplier;

        $this->customers = Customer::factory()->count(2)->create([
            'sales_rep_id' => $this->user->id,
            'is_active'    => true,
            'panel_sold'   => true
        ]);

        $this->customers->each(function ($customer) {
            $this->userPersonalStockPoints->push(CustomersStockPoint::factory()->create(['customer_id' => $customer->id]));
        });

        $this->teamStockPoints->push(Customer::factory()->has(CustomersStockPoint::factory(),'stockPoint')
            ->create([
                'setter_id' => $this->user->id,
                'is_active'    => true,
                'panel_sold'   => true
            ])->stockPoint
        );

        $this->teamStockPoints->push(Customer::factory()->has(CustomersStockPoint::factory(),'stockPoint')
            ->create([
                'sales_rep_recruiter_id' => $this->user->id,
                'is_active'              => true,
                'panel_sold'             => true
            ])->stockPoint
        );

        $this->teamStockPoints->push(Customer::factory()->has(CustomersStockPoint::factory(),'stockPoint')
            ->create([
                'office_manager_id' => $this->user->id,
                'is_active'         => true,
                'panel_sold'        => true
            ])->stockPoint
        );

        $this->teamStockPoints->push(Customer::factory()->has(CustomersStockPoint::factory(),'stockPoint')
            ->create([
                'region_manager_id' => $this->user->id,
                'is_active'         => true,
                'panel_sold'        => true
            ])->stockPoint
        );

        $this->teamStockPoints->push(Customer::factory()->has(CustomersStockPoint::factory(),'stockPoint')
            ->create([
                'department_manager_id' => $this->user->id,
                'is_active'         => true,
                'panel_sold'        => true
            ])->stockPoint
        );
        
    }

    /** @test */
    public function it_should_show_stock_points_card()
    {
        $this->get('/')->assertSee('STOCK SHARES');
    }

    /** @test */
    public function it_should_show_personal_stock_points()
    {
        $this->get('/')->assertSee($this->userPersonalStockPoints->sum('stock_personal_sale'));
    }
    
    /** @test */
    public function it_should_show_team_stock_points()
    {
        $this->get('/')->assertSee(
            $this->teamStockPoints[0]->stock_setting + 
            $this->teamStockPoints[1]->stock_recruiter + 
            $this->teamStockPoints[2]->stock_manager +
            $this->teamStockPoints[3]->stock_regional +
            $this->teamStockPoints[4]->stock_department
        );
    }

    /** @test */
    public function it_should_show_total_stock_points()
    {
        $this->get('/')->assertSee(
            $this->userPersonalStockPoints->sum('stock_personal_sale') +
            $this->teamStockPoints[0]->stock_setting + 
            $this->teamStockPoints[1]->stock_recruiter + 
            $this->teamStockPoints[2]->stock_manager +
            $this->teamStockPoints[3]->stock_regional +
            $this->teamStockPoints[4]->stock_department
        );
    }

    /** @test */
    public function it_should_show_gross_stock_points()
    {
        $this->get('/')->assertSee(
            round(($this->userPersonalStockPoints->sum('stock_personal_sale') +
            $this->teamStockPoints[0]->stock_setting + 
            $this->teamStockPoints[1]->stock_recruiter + 
            $this->teamStockPoints[2]->stock_manager +
            $this->teamStockPoints[3]->stock_regional +
            $this->teamStockPoints[4]->stock_department) * $this->multiplier)
        );
    }
}
