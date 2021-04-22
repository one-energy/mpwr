<?php

namespace Tests\Feature\NumberTracker;

use App\Models\DailyNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreNumberTrackerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'Admin']);
    }

    /** @test */
    public function it_should_store_daily_numbers()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(),
            ],
        ];

        $this->assertDatabaseCount('daily_numbers', 0);

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data);

        $this->assertDatabaseCount('daily_numbers', 1);

        $this->assertDatabaseHas('daily_numbers',  ['user_id' => $mary->id]);
    }

    /** @test */
    public function it_should_update_if_daily_numbers_already_exists_on_provided_date()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $date = now();

        /** @var DailyNumber */
        $dailyNumber = DailyNumber::factory()->create(
            array_merge([
                'user_id' => $mary->id,
                'date'    => $date,
            ], $this->makeNumberTrackingArray())
        );

        $newNumbersTracking = $this->makeNumberTrackingArray([
            'doors' => 5,
            'hours' => 10,
        ]);

        $data = [
            'officeSelected' => 1,
            'date'           => $date,
            'numbers'        => [
                $mary->id => $newNumbersTracking,
            ],
        ];

        $this->assertDatabaseCount($dailyNumber->getTable(), 1);

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data);

        $this->assertDatabaseCount($dailyNumber->getTable(), 1);

        $this->assertDatabaseHas($dailyNumber->getTable(),  [
            'user_id' => $mary->id,
            'doors'   => 5,
            'hours'   => 10,
        ]);
    }

    /** @test */
    public function it_should_require_officeSelected()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'date'    => now(),
            'numbers' => [
                $mary->id => [],
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors('officeSelected');
    }

    /** @test */
    public function it_should_prevent_negative_doors_quantity()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['doors' => -1]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.doors', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_hours_quantity()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['hours' => -1]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.hours', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_sets_quantity()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['sets' => -1]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.sets', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_sits_quantity()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['sits' => -1]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.sits', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_set_closes_quantity()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['set_closes' => -1]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.set_closes', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_closes_quantity()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['closes' => -1]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.closes', $mary->id));
    }

    /** @test */
    public function it_should_prevent_hours_quantity_above_24()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['hours'  => '24.01']),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.hours', $mary->id));
    }

    /** @test */
    public function it_should_prevent_that_sets_quantity_be_greater_than_doors_quantity()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray([
                    'doors' => 1,
                    'sets'  => 2,
                ]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.doors', $mary->id));
    }

    /** @test */
    public function it_should_prevent_that_closes_quantity_be_greater_than_sets_quantity()
    {
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray([
                    'doors'   => 1,
                    'sets'    => 1,
                    'closes'  => 2,
                ]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
        ->assertSessionHasErrors(sprintf('numbers.%s.sets', $mary->id));
    }

    private function makeNumberTrackingArray(array $attributes = [])
    {
        return array_merge([
            'doors'      => '0',
            'hours'      => '0',
            'sets'       => '0',
            'set_sits'   => '0',
            'sits'       => '0',
            'set_closes' => '0',
            'closes'     => '0',
        ], $attributes);
    }
}