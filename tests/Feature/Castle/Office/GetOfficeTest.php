<?php

namespace Tests\Feature\Castle\Office;

use App\Enum\Role;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetOfficeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_offices()
    {
        [$departmentManager, $department] = $this->createVP();

        $regionManager = User::factory()->create([
            'department_id' => $department->id,
            'role'          => Role::REGION_MANAGER,
        ]);

        /** @var Region $region */
        $region = Region::factory()->create(['department_id' => $department->id]);
        $region->managers()->attach(($regionManager->id));

        $officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $offices = Office::factory()
            ->count(6)
            ->create(['region_id' => $region->id])
        ->each(fn (Office $office) => $office->managers()->attach($officeManager->id));

        $response = $this
            ->actingAs($departmentManager)
            ->get(route('castle.offices.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.offices.index');

        foreach ($offices as $office) {
            $response->assertSee($office->name);
        }
    }
}
