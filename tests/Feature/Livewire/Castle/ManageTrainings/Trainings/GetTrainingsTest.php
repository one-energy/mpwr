<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings\Trainings;

use App\Enum\Role;
use App\Http\Livewire\Castle\ManageTrainings\Trainings;
use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class GetTrainingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_change_tabs()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $department = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this->actingAs($john);

        Livewire::test(Trainings::class, ['department' => $department])
            ->assertSet('filesTabSelected', true)
            ->assertSet('trainingTabSelected', false)
            ->call('changeTab', 'training')
            ->assertSet('filesTabSelected', false)
            ->assertSet('trainingTabSelected', true)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_attach_first_department_in_db_if_auth_user_is_admin_and_no_department_provided()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Collection $departments */
        $departments = Department::factory()->times(2)->create();
        TrainingPageSection::factory()->create(['department_id' => $departments->first()->id]);

        $this->actingAs($john);

        Livewire::test(Trainings::class)
            ->assertSet('department.id', $departments->first()->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_change_department()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $department01 = Department::factory()->create();
        $department02 = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department01->id]);

        $this->actingAs($john);

        Livewire::test(Trainings::class, ['department' => $department01])
            ->call('changeDepartment', $department02->id)
            ->assertRedirect(route('castle.manage-trainings.index', ['department' => $department02->id]))
            ->assertHasNoErrors();

        Livewire::test(Trainings::class, ['department' => $department01])
            ->call('changeDepartment', 99)
            ->assertHasNoErrors();
    }
}
