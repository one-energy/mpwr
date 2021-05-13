<?php

namespace Tests\Feature\Profile;

use App\Models\Customer;
use App\Models\CustomersStockPoint;
use App\Models\Term;
use App\Models\User;
use App\Models\UserCustomersEniumPoints;
use App\Models\UserEniumPointLevel;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class StockPointsTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public Customer $customer;
    public Collection $userPersonalStockPoints;

    protected function setUp ():void
    {
        parent::setUp();
        $this->userPersonalStockPoints = collect([]);
        $this->user = User::factory()->create(['role' => 'Sales Rep']);
        $this->actingAs($this->user);

        $this->customers = Customer::factory()->count(2)->create([
            'sales_rep_id' => $this->user->id,
            'is_active'    => true,
            'panel_sold'   => true
        ]);

        $this->customers->each(function ($customer) {
            $this->userPersonalStockPoints->push(CustomersStockPoint::factory()->create(['customer_id' => $customer->id]));
        });
    }

    /** @test */
    public function it_should_show_stock_points_card()
    {
        $response = $this->get('/');

        $response->assertSee('STOCK SHARES');
    }

    /** @test */
    public function it_should_show_personal_stock_points()
    {
        $this->get('/')->assertSee($this->userPersonalStockPoints->sum('stock_personal_sale'));
    }

    /** @test */
    public function it_should_show_team_stock_points()
    {
    }

    /** @test */
    public function it_should_show_total_stock_points()
    {
    }

    /** @test */
    public function it_should_show_gross_stock_points()
    {
    }
}
