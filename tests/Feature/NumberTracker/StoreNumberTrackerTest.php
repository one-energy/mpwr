<?php

namespace Tests\Feature\NumberTracker;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreNumberTrackerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_require_officeSelected()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $data =[
            'date'    => now(),
            'numbers' => [],
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors('officeSelected');
    }

    /** @test */
    public function it_should_require_numbers()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors('numbers');
    }

    /** @test */
    public function it_should_prevent_negative_doors_quantity()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['doors' => -1]),
            ],
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.doors', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_hours_quantity()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['hours' => -1]),
            ],
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.hours', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_sets_quantity()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['sets' => -1]),
            ],
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.sets', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_sits_quantity()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['sits' => -1]),
            ],
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.sits', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_set_closes_quantity()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['set_closes' => -1]),
            ],
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.set_closes', $mary->id));
    }

    /** @test */
    public function it_should_prevent_negative_closes_quantity()
    {
        $john = User::factory()->create(['role' => 'Admin']);
        $mary = User::factory()->create(['role' => 'Setter']);

        $data = [
            'officeSelected' => 1,
            'date'           => now(),
            'numbers'        => [
                $mary->id => $this->makeNumberTrackingArray(['closes' => -1]),
            ],
        ];

        $this
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.closes', $mary->id));
    }

    /** @test */
    public function it_should_prevent_that_sets_quantity_be_greater_than_doors_quantity()
    {
        $john = User::factory()->create(['role' => 'Admin']);
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
            ->actingAs($john)
            ->post(route('number-tracking.store'), $data)
            ->assertSessionHasErrors(sprintf('numbers.%s.doors', $mary->id));
    }

    /** @test */
    public function it_should_prevent_that_closes_quantity_be_greater_than_sets_quantity()
    {
        $john = User::factory()->create(['role' => 'Admin']);
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
            ->actingAs($john)
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
