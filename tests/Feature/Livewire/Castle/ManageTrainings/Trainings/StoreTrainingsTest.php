<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings\Trainings;

use App\Http\Livewire\Castle\ManageTrainings\Trainings;
use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoreTrainingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_store_a_video()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $department = Department::factory()->create();
        $section    = TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this->actingAs($john);

        $this->assertDatabaseCount('training_page_contents', TrainingPageContent::count());

        Livewire::test(Trainings::class, ['department' => $department])
            ->set('video.title', 'title')
            ->set('video.video_url', 'url')
            ->set('video.description', 'description')
            ->call('storeVideo')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('training_page_contents', TrainingPageContent::count());
        $this->assertDatabaseHas('training_page_contents', [
            'title'                    => 'title',
            'video_url'                => 'url',
            'description'              => 'description',
            'training_page_section_id' => $section->id,
        ]);
    }

    /** @test */
    public function it_should_require_title_to_store_video()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $department = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this->actingAs($john);

        $this->assertDatabaseCount('training_page_contents', TrainingPageContent::count());

        Livewire::test(Trainings::class, ['department' => $department])
            ->set('video.video_url', 'url')
            ->set('video.description', 'description')
            ->call('storeVideo')
            ->assertHasErrors(['video.title' => 'required']);
    }

    /** @test */
    public function it_should_require_video_url_to_store_video()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $department = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this->actingAs($john);

        $this->assertDatabaseCount('training_page_contents', TrainingPageContent::count());

        Livewire::test(Trainings::class, ['department' => $department])
            ->set('video.title', 'title')
            ->set('video.description', 'description')
            ->call('storeVideo')
            ->assertHasErrors(['video.video_url' => 'required']);
    }

    /** @test */
    public function it_should_require_description_to_store_video()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $department = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this->actingAs($john);

        $this->assertDatabaseCount('training_page_contents', TrainingPageContent::count());

        Livewire::test(Trainings::class, ['department' => $department])
            ->set('video.title', 'title')
            ->set('video.video_url', 'url')
            ->call('storeVideo')
            ->assertHasErrors(['video.description' => 'required']);
    }
}
