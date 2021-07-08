<?php

namespace Tests\Feature\NumberTracker\Spreadsheet;

use App\Enum\Role;
use App\Models\DailyNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOrCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_prevent_a_sales_rep_or_setter_store_or_update_daily_numbers()
    {
        $john = User::factory()->create(['role' => Role::SETTER]);
        $mary = User::factory()->create(['role' => Role::SALES_REP]);

        $this
            ->actingAs($john)
            ->post(route('number-tracking.spreadsheet.updateOrCreate'), [])
            ->assertNotFound();

        $this
            ->actingAs($mary)
            ->post(route('number-tracking.spreadsheet.updateOrCreate'), [])
            ->assertNotFound();
    }

    /** @test */
    public function it_should_store_daily_numbers()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $dummy01 */
        $dummy01 = User::factory()->create(['role' => Role::SETTER]);
        /** @var User $dummy02 */
        $dummy02 = User::factory()->create(['role' => Role::SETTER]);

        $this->assertDatabaseCount('daily_numbers', 0);
        $this->assertCount(0, $dummy01->dailyNumbers);
        $this->assertCount(0, $dummy02->dailyNumbers);

        $this->actingAs($john)
            ->post(route('number-tracking.spreadsheet.updateOrCreate'), [
                'dailyNumbers' => [
                    [
                        [
                            [
                                'user_id'       => $dummy01->id,
                                'office_id'     => $dummy01->office_id,
                                'date'          => 'May 01st',
                                'doors'         => 1,
                                'hours_knocked' => 1,
                                'sets'          => 1,
                                'sats'          => 1,
                                'set_closes'    => 1,
                                'closer_sits'   => 1,
                                'closes'        => 1,
                            ],
                            [
                                'user_id'       => $dummy02->id,
                                'office_id'     => $dummy02->office_id,
                                'date'          => 'May 02st',
                                'doors'         => 2,
                                'hours_knocked' => 2,
                                'sets'          => 2,
                                'sats'          => 2,
                                'set_closes'    => 2,
                                'closer_sits'   => 2,
                                'closes'        => 2,
                            ],
                        ],
                    ],
                ],
            ]);

        $this->assertDatabaseCount('daily_numbers', 2);
        $this->assertCount(1, $dummy01->fresh()->dailyNumbers);
        $this->assertCount(1, $dummy02->fresh()->dailyNumbers);
    }

    /** @test */
    public function it_should_update_daily_numbers()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $dummy01 */
        $dummy01   = User::factory()->create(['role' => Role::SETTER]);
        $tracker01 = DailyNumber::factory()->create(['user_id' => $dummy01->id]);

        /** @var User $dummy02 */
        $dummy02   = User::factory()->create(['role' => Role::SETTER]);
        $tracker02 = DailyNumber::factory()->create(['user_id' => $dummy02->id]);

        $this->assertDatabaseCount('daily_numbers', 2);
        $this->assertCount(1, $dummy01->dailyNumbers);
        $this->assertCount(1, $dummy02->dailyNumbers);

        $this->actingAs($john)
            ->post(route('number-tracking.spreadsheet.updateOrCreate'), [
                'dailyNumbers' => [
                    [
                        [
                            [
                                'id'            => $tracker01->id,
                                'user_id'       => $dummy01->id,
                                'office_id'     => $dummy01->office_id,
                                'date'          => 'May 01st',
                                'doors'         => 1,
                                'hours_knocked' => 1,
                                'sets'          => 1,
                                'sats'          => 1,
                                'set_closes'    => 1,
                                'closer_sits'   => 1,
                                'closes'        => 1,
                            ],
                            [
                                'id'            => $tracker02->id,
                                'office_id'     => $dummy02->office_id,
                                'date'          => 'May 02st',
                                'doors'         => 2,
                                'hours_knocked' => 2,
                                'sets'          => 2,
                                'sats'          => 2,
                                'set_closes'    => 2,
                                'closer_sits'   => 2,
                                'closes'        => 2,
                            ],
                        ],
                    ],
                ],
            ]);

        $this->assertDatabaseCount('daily_numbers', 2);
        $this->assertCount(1, $dummy01->fresh()->dailyNumbers);
        $this->assertCount(1, $dummy02->fresh()->dailyNumbers);

        $this->assertDatabaseHas('daily_numbers', [
            'id'            => $tracker01->id,
            'doors'         => 1,
            'hours_worked'  => 4,
            'hours_knocked' => 1,
            'sets'          => 1,
            'sats'          => 1,
            'set_closes'    => 1,
            'closer_sits'   => 1,
            'closes'        => 1,
        ]);

        $this->assertDatabaseHas('daily_numbers', [
            'id'            => $tracker02->id,
            'doors'         => 2,
            'hours_worked'  => 8,
            'hours_knocked' => 2,
            'sets'          => 2,
            'sats'          => 2,
            'set_closes'    => 2,
            'closer_sits'   => 2,
            'closes'        => 2,
        ]);
    }

    /** @test */
    public function it_should_store_when_the_array_does_not_have_an_id_and_update_when_have_it()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $dummy01 */
        $dummy01   = User::factory()->create(['role' => Role::SETTER]);
        $tracker01 = DailyNumber::factory()->create(['user_id' => $dummy01->id]);

        /** @var User $dummy02 */
        $dummy02 = User::factory()->create(['role' => Role::SETTER]);

        $this->assertDatabaseCount('daily_numbers', 1);
        $this->assertCount(1, $dummy01->dailyNumbers);
        $this->assertCount(0, $dummy02->dailyNumbers);

        $this->actingAs($john)
            ->post(route('number-tracking.spreadsheet.updateOrCreate'), [
                'dailyNumbers' => [
                    [
                        [
                            [
                                'id'            => $tracker01->id,
                                'user_id'       => $dummy01->id,
                                'office_id'     => $dummy01->office_id,
                                'date'          => 'May 01st',
                                'doors'         => 1,
                                'hours_knocked' => 1,
                                'sets'          => 1,
                                'sats'          => 1,
                                'set_closes'    => 1,
                                'closer_sits'   => 1,
                                'closes'        => 1,
                            ],
                            [
                                'user_id'       => $dummy02->id,
                                'office_id'     => $dummy02->office_id,
                                'date'          => 'May 02st',
                                'doors'         => 2,
                                'hours_knocked' => 2,
                                'sets'          => 2,
                                'sats'          => 2,
                                'set_closes'    => 2,
                                'closer_sits'   => 2,
                                'closes'        => 2,
                            ],
                        ],
                    ],
                ],
            ]);

        $this->assertDatabaseCount('daily_numbers', 2);
        $this->assertCount(1, $dummy01->fresh()->dailyNumbers);
        $this->assertCount(1, $dummy02->fresh()->dailyNumbers);

        $this->assertDatabaseHas('daily_numbers', [
            'id'            => $tracker01->id,
            'doors'         => 1,
            'hours_worked'  => 4,
            'hours_knocked' => 1,
            'sets'          => 1,
            'sats'          => 1,
            'set_closes'    => 1,
            'closer_sits'   => 1,
            'closes'        => 1,
        ]);

        $this->assertDatabaseHas('daily_numbers', [
            'id'            => $dummy02->fresh()->dailyNumbers->first()->id,
            'doors'         => 2,
            'hours_worked'  => 8,
            'hours_knocked' => 2,
            'sets'          => 2,
            'sats'          => 2,
            'set_closes'    => 2,
            'closer_sits'   => 2,
            'closes'        => 2,
        ]);
    }
}
