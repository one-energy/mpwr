<?php

namespace Tests\Feature\NumberTracker;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\NumbersRatios;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class RatiosComponentTest extends TestCase
{
    use RefreshDatabase;

    public User $dptManager;

    public User $regionManager;

    public User $officeManager;

    public User $john;

    public Department $department;

    public Region $region;

    public Office $office;

    public Office $officeTwo;

    public DailyNumber $dptManagerEntry;

    public DailyNumber $regionManagerEntry;

    public DailyNumber $officeManagerEntry;

    public DailyNumber $johnEntry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dptManager    = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $this->regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $this->officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $this->department = Department::factory()->create([
            'department_manager_id' => $this->dptManager->id,
        ]);

        $this->region = Region::factory()->create([
            'department_id'     => $this->department->id,
            'region_manager_id' => $this->regionManager->id,
        ]);

        $this->office = Office::factory()->create([
            'region_id'         => $this->region->id,
            'office_manager_id' => $this->officeManager->id,
        ]);

        $this->officeTwo = Office::factory()->create([
            'region_id'         => $this->region->id,
            'office_manager_id' => $this->officeManager->id,
        ]);

        $this->john = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->dptManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->regionManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->officeManager->update([
            'department_id' => $this->department->id,
            'office_id'     => $this->office->id,
        ]);

        $this->actingAs($this->dptManager);

        $this->officeManagerEntry = DailyNumber::factory()->create([
            'user_id'   => $this->officeManager->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::now(),
        ]);

        $this->dptManagerEntry = DailyNumber::factory()->create([
            'user_id'   => $this->dptManager->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::now(),
        ]);

        $this->regionManagerEntry = DailyNumber::factory()->create([
            'user_id'   => $this->regionManager->id,
            'office_id' => $this->office->id,
            'date'      => Carbon::now(),
        ]);

        $this->johnEntry = DailyNumber::factory()->create([
            'user_id'   => $this->john->id,
            'office_id' => $this->officeTwo->id,
            'date'      => Carbon::now(),
        ]);
    }

    /** @test */
    public function it_should_show_ratios()
    {
        Livewire::test(NumbersRatios::class)
            ->assertSee('D.P.S')
            ->assertSee('HK.P. SET')
            ->assertSee('Sit Ratio')
            ->assertSee('Close Ratio');
    }

    /** @test */
    public function it_should_receive_event_an_set_offices_users_deleted()
    {
        $officeArray = [2, 4, 6];
        $userArray   = [4, 2, 3];

        Livewire::test(NumbersRatios::class)
            ->emit('updateNumbers', [
                'offices' => $officeArray,
                'users'   => $userArray,
            ])
            ->assertSet('offices', $officeArray)
            ->assertSet('users', $userArray);
    }

    /** @test */
    public function it_should_sum_dps()
    {
        [$officeArray, $userArray] = $this->getUserAndOfficeIds();

        $doors = $this->sumBy('doors');
        $sets  = $this->sumBy('sets');

        Livewire::test(NumbersRatios::class)
            ->emit('updateNumbers', [
                'offices' => $officeArray,
                'users'   => $userArray,
            ])
            ->assertSeeInOrder(['D.P.S', round($doors / $sets, 2), 'HK.P. SET']);
    }

    /** @test */
    public function it_should_sum_hps()
    {
        [$officeArray, $userArray] = $this->getUserAndOfficeIds();

        $hoursWorked = $this->sumBy('hours_knocked');
        $sets        = $this->sumBy('sets');

        Livewire::test(NumbersRatios::class)
            ->emit('updateNumbers', [
                'offices' => $officeArray,
                'users'   => $userArray,
            ])
            ->assertSeeInOrder(['HK.P. SET', round($hoursWorked / $sets, 2), 'Sit Ratio']);
    }

    /** @test */
    public function it_should_sum_sit_ratios()
    {
        [$officeArray, $userArray] = $this->getUserAndOfficeIds();

        $sats    = $this->sumBy('sats');
        $sets    = $this->sumBy('sets');

        Livewire::test(NumbersRatios::class)
            ->emit('updateNumbers', [
                'offices' => $officeArray,
                'users'   => $userArray,
            ])
            ->assertSeeInOrder(['Sit Ratio', round($sets / $sats, 2), 'Close Ratio']);
    }

    /** @test */
    public function it_should_sum_close_ratios()
    {
        [$officeArray, $userArray] = $this->getUserAndOfficeIds();

        $closerSits = $this->sumBy('closer_sits');
        $closes     = $this->sumBy('closes');

        Livewire::test(NumbersRatios::class)
            ->emit('updateNumbers', [
                'offices' => $officeArray,
                'users'   => $userArray,
            ])
            ->assertSeeInOrder(['Close Ratio', round(($closerSits / $closes), 2)]);
    }

    /** @test */
    public function it_should_sum_deleteds()
    {
        $this->officeTwo->delete();
        $this->john->delete();
        $this->johnEntry->delete();

        [$officeArray, $userArray] = $this->getUserAndOfficeIds();

        $doors = $this->sumBy('doors');
        $sets  = $this->sumBy('sets');

        Livewire::test(NumbersRatios::class)
            ->emit('updateNumbers', [
                'offices' => $officeArray,
                'users'   => $userArray,
            ])
            ->assertSee(round($doors / $sets, 2));
    }

    /** @test */
    public function it_should_only_sum_selected_items()
    {
        $officeArray = [$this->office->id];
        $userArray   = [$this->dptManager->id, $this->regionManager->id, $this->officeManager->id];

        $sumDoors = $this->dptManagerEntry->doors + $this->regionManagerEntry->doors + $this->officeManagerEntry->doors;
        $sumSets  = $this->dptManagerEntry->sets + $this->regionManagerEntry->sets + $this->officeManagerEntry->sets;

        Livewire::test(NumbersRatios::class)
            ->emit('updateNumbers', [
                'offices' => $officeArray,
                'users'   => $userArray,
            ])
            ->assertSee(round($sumDoors / $sumSets, 2));
    }

    private function sumBy(string $field): mixed
    {
        return $this->dptManagerEntry->{$field} +
            $this->regionManagerEntry->{$field} +
            $this->officeManagerEntry->{$field} +
            $this->johnEntry->{$field};
    }

    private function getUserAndOfficeIds(): array
    {
        $officeArray = [$this->office->id, $this->officeTwo->id];
        $userArray   = [$this->dptManager->id, $this->regionManager->id, $this->officeManager->id, $this->john->id];

        return [$officeArray, $userArray];
    }
}
