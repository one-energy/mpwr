<?php

namespace Tests\Feature\NumberTracker;

use App\Enum\Role;
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

        $this->admin = User::factory()->create(['role' => Role::ADMIN]);
    }

    /** @test */
    public function it_should_store_daily_numbers()
    {
        $mary = User::factory()->create(['role' => Role::SETTER]);

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

        $this->assertDatabaseHas('daily_numbers', ['user_id' => $mary->id]);
    }

    /** @test */
    public function it_should_return_a_flash_message_if_no_numbers_provided()
    {
        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHas('alert');
    }

    /** @test */
    public function it_should_update_if_daily_numbers_already_exists_on_provided_date()
    {
        $mary = User::factory()->create(['role' => Role::SETTER]);

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

        $this->assertDatabaseHas($dailyNumber->getTable(), [
            'user_id' => $mary->id,
            'doors'   => 5,
            'hours'   => 10,
        ]);
    }

    /** @test */
    public function it_should_require_officeSelected()
    {
        $mary = User::factory()->create(['role' => Role::SETTER]);

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
        $mary = User::factory()->create(['role' => Role::SETTER]);

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
    public function it_should_prevent_negative_hours_worked_quantity()
    {
        $mary = User::factory()->create(['role' => Role::SETTER]);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['hours_worked' => -1]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.hours_worked', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_sets_quantity()
    {
        $mary = User::factory()->create(['role' => Role::SETTER]);

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
    public function it_should_prevent_negative_sats_quantity()
    {
        $mary = User::factory()->create(['role' => Role::SETTER]);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['sats' => -1]),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.sats', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_set_closes_quantity()
    {
        $mary = User::factory()->create(['role' => Role::SETTER]);

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
        $mary = User::factory()->create(['role' => Role::SETTER]);

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
    public function it_should_prevent_hours_worked_quantity_above_24()
    {
        $mary = User::factory()->create(['role' => Role::SETTER]);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['hours_worked' => '24.01']),
            ],
        ];

        $this
            ->actingAs($this->admin)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.hours_worked', $mary->id));
    }

    private function makeNumberTrackingArray(array $attributes = [])
    {
        return array_merge([
            'hours_worked'  => '0',
            'doors'         => '0',
            'hours_knocked' => '0',
            'sets'          => '0',
            'sats'          => '0',
            'set_closes'    => '0',
            'closer_sits'   => '0',
            'closes'        => '0',
        ], $attributes);
    }
}
