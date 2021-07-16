<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Builders\OfficeBuilder;
use Tests\Builders\RegionBuilder;
use Tests\TestCase;

class GetTopTenTrackersTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private User $admin;

    /** @var User */
    private User $departmentManager;

    /** @var User */
    private User $regionManager;

    /** @var User */
    private User $officeManager;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var User admin */
        $this->admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->createManagers();
        $this->createDepartment();
    }

    /** @test */
    public function it_should_be_possible_get_top_users_by_doors_in_desc_order()
    {
        $department = $this->createDepartment();
        $region     = $this->createRegion($department);
        $office     = $this->createOffice($region);

        $setter01 = $this->createSetter($department, $office);
        $setter02 = $this->createSetter($department, $office);

        $this->createDailyNumber($setter01, $office, ['date' => today(), 'doors' => 1]);
        $this->createDailyNumber($setter02, $office, ['date' => today(), 'doors' => 2]);

        $this->actingAs($this->admin);

        Livewire::test(NumberTrackerDetail::class, [
            'selectedDepartment' => $department->id,
            'selectedUsersIds'   => [$setter01->id, $setter02->id],
            'selectedOfficesIds' => [$office->id],
        ])
            ->set('selectedLeaderboardPill', 'doors')
            ->assertSet('selectedLeaderboardPill', 'doors')
            ->assertCount('topTenTrackers', 2)
            ->assertSeeInOrder([$setter02->full_name, $setter01->full_name])
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_order_by_sets ()
    {
        $department = $this->createDepartment();
        $region     = $this->createRegion($department);
        $office     = $this->createOffice($region);

        $setter01 = $this->createSetter($department, $office);
        $setter02 = $this->createSetter($department, $office);

        $this->createDailyNumber($setter01, $office, ['date' => today(), 'sets' => 2]);
        $this->createDailyNumber($setter02, $office, ['date' => today(), 'sets' => 1]);

        $this->actingAs($this->admin);

        Livewire::test(NumberTrackerDetail::class, [
            'selectedDepartment' => $department->id,
            'selectedUsersIds'   => [$setter01->id, $setter02->id],
            'selectedOfficesIds' => [$office->id],
        ])
            ->set('selectedLeaderboardPill', 'sets')
            ->assertSet('selectedLeaderboardPill', 'sets')
            ->assertCount('topTenTrackers', 2)
            ->assertSeeInOrder([$setter01->full_name, $setter02->full_name])
            ->assertHasNoErrors();
    }

    private function createManagers(): void
    {
        /** @var User departmentManager */
        $this->departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var User regionManager */
        $this->regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var User officeManager */
        $this->officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
    }

    private function createDepartment(?User $manager = null): Department
    {
        $manager = $manager ?? $this->departmentManager;

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($manager->id);

        return $department;
    }

    private function createRegion(Department $department, ?User $manager = null): Region
    {
        $manager = $manager ?? $this->regionManager;

        return RegionBuilder::build()
            ->withManager($manager)
            ->withDepartment($department)
            ->save()
            ->get();
    }

    private function createOffice(Region $region, ?User $manager = null): Office
    {
        $manager = $manager ?? $this->officeManager;

        return OfficeBuilder::build()
            ->withManager($manager)
            ->region($region)
            ->save()
            ->get();
    }

    private function createSetter(Department $department, Office $office): User
    {
        return User::factory()->create([
            'department_id' => $department->id,
            'office_id'     => $office->id,
            'role'          => Role::SETTER,
        ]);
    }

    private function createDailyNumber(User $setter, Office $office, array $attributes = []): DailyNumber
    {
        $defaultAttr = [
            'user_id'   => $setter->id,
            'office_id' => $office->id,
        ];

        return DailyNumber::factory()->create(array_merge($defaultAttr, $attributes));
    }
}
