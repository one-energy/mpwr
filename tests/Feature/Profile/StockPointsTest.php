<?php

namespace Tests\Feature\Profile;

use App\Models\Customer;
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
    use DatabaseTransactions;

    public User $user;
    public Customer $customer;
    public Collection $userEniumPoints;

    protected function setUp ():void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'Sales Rep']);

        $this->customers = Customer::factory()->count(2)->create([
            'term_id'      => Term::inRandomOrder()->first()->id,
            'sales_rep_id' => $this->user->id,
            'is_active'    => true,
            'panel_sold'   => true
        ]);

        $this->customers->each(function ($customer) {
            $this->userEniumPoints->push(
                UserCustomersEniumPoints::factory()->create([ 
                    'user_sales_rep_id' => $this->user->id,
                    'customer_id'       => $customer->id,
                    'points'            => round($customer->epc/$customer->term->amount),
                    'set_date'          => Carbon::now(),
                    'expiration_date'   => Carbon::now()->addYear()
                ])
            );  
        });

        UserEniumPointLevel::factory()->count(12)
            ->state(new Sequence(
                ['level' => 1],
                ['level' => 2],
                ['level' => 3],
                ['level' => 4],
                ['level' => 5],
                ['level' => 6],
                ['level' => 7],
                ['level' => 8],
                ['level' => 9],
                ['level' => 10],
                ['level' => 11],
                ['level' => 12]
            ))
            ->state(new Sequence(
                ['point' => 500],
                ['point' => 1000],
                ['point' => 1500],
                ['point' => 2000],
                ['point' => 2500],
                ['point' => 3000],
                ['point' => 3500],
                ['point' => 4000],
                ['point' => 4500],
                ['point' => 5000],
                ['point' => 5500],
                ['point' => 6000]
            ))
            ->create();
    
        $this->actingAs($this->user);
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
