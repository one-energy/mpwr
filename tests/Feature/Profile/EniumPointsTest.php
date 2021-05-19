<?php

namespace Tests\Feature\Profile;

use App\Models\Customer;
use App\Models\Term;
use App\Models\User;
use App\Models\UserCustomersEniumPoints;
use App\Models\UserEniumPointLevel;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class EniumPointsTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public Customer $customer;
    public Collection $userEniumPoints;

    protected function setUp ():void
    {
        parent::setUp();

        $this->userEniumPoints = collect([]);

        $this->user = User::factory()->create(['role' => 'Sales Rep']);

        $this->actingAs($this->user);

        Term::factory()->count(4)
        ->state(new Sequence(
            ['amount' => 480],
            ['amount' => 800]
        ))
        ->create();

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
                    'points'            => round($customer->totalSoldPrice/$customer->term->amount),
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
    }

    /** @test */
    public function it_should_show_enium_points_card()
    {
        $response = $this->get('/');

        $response->assertSee('ENIUM POINTS');
    }

    /** @test */
    public function it_should_show_enium_points_level()
    {
        $jhon     = User::factory()->create(['role' => 'Sales Rep']);
        $customer = Customer::factory()->create([
            'sales_rep_id' => $jhon->id,
            'is_active'    => true,
            'panel_sold'   => true
        ]);

        UserCustomersEniumPoints::factory()->create([ 
            'user_sales_rep_id' => $customer->sales_rep_id,
            'customer_id'       => $customer->id, 
            'points'            => 2500,
            'set_date'          => Carbon::now(),
            'expiration_date'   => Carbon::now()->addYear()
        ]);

        $this->assertEquals($jhon->level()->level, 5);
    }

    /** @test */
    public function it_should_show_enium_sum_points()
    {
        $jhon     = User::factory()->create(['role' => 'Sales Rep']);
        $customer = Customer::factory()->create([
            'epc'          => 2500,
            'term_id'      => Term::inRandomOrder()->first()->id,
            'sales_rep_id' => $jhon->id,
            'is_active'    => true,
            'panel_sold'   => true
        ]);

        UserCustomersEniumPoints::factory()->create([ 
            'user_sales_rep_id' => $customer->sales_rep_id,
            'customer_id'       => $customer->id, 
            'points'            => round($customer->totalSoldPrice/$customer->term->amount),
            'set_date'          => Carbon::now(),
            'expiration_date'   => Carbon::now()->addYear()
        ]);

        $this->get('/')
            ->assertSee($this->userEniumPoints->sum('points'));
    }
}
