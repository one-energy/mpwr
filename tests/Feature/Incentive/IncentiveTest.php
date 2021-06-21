<?php

namespace Tests\Feature\Incentive;

use App\Models\Customer;
use App\Models\Department;
use App\Models\Incentive;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncentiveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $department = Department::factory()->create(['id' => 1]);
        Incentive::factory()->create([
            'name'            => 'incentive test',
            'installs_needed' => 100,
            'kw_needed'       => 100,
            'department_id'   => $department->id,
        ]);

        $this->user = User::factory()->create([
            'role'          => Role::OFFICE_MANAGER,
            'installs'      => 10,
            'kw_achived'    => 20,
            'department_id' => $department->id,
        ]);

        Customer::factory()->create([
            'sales_rep_id' => $this->user->id,
            'panel_sold'   => true,
            'is_active'    => true
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_incentives()
    {
        $this->get(route('incentives.index'))
            ->assertOk();
    }


    /** @test */
    public function it_should_calc_percents_of_incentives()
    {
        $this->get(route('incentives.index'))
            ->assertOk()
            ->assertSee('incentive test')
            ->assertSee('1.00%')
            ->assertSee('20.00%');
    }
}
