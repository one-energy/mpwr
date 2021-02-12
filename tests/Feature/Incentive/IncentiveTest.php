<?php

namespace Tests\Feature\Incentive;

use App\Models\Department;
use App\Models\Incentive;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncentiveTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $department = factory(Department::class)->create(['id' => 1]);
        factory(Incentive::class)->create([
            'name'            => 'incentive test',
            'installs_needed' => 100,
            'kw_needed'       => 100,
            'department_id'   => $department->id
        ]);
        $this->user = factory(User::class)->create([
            'role' => 'Office Manager',
            'installs'  => 10,
            'kw_achived'  => 20,
            'department_id' => $department->id
        ]);
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_incentives()
    {
        $response = $this->get('incentives');

        $response->assertStatus(200);
    }


    /** @test */
    public function it_should_calc_percents_of_incentives()
    {
        $response = $this->get('incentives');

        $response->assertStatus(200)
            ->assertSee('incentive test')
            ->assertSee('10.00%')
            ->assertSee('20.00%');
    }
}
