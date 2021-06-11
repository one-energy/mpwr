<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings\Trainings;

use App\Http\Livewire\Castle\ManageTrainings\Trainings;
use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSet('trainingTabSelected', true);
    }
}
