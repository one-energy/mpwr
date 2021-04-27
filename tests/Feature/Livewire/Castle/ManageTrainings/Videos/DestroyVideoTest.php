<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings\Videos;

use App\Http\Livewire\Castle\ManageTrainings\Videos;
use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class DestroyVideoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_delete_a_video()
    {
        $john = User::factory()->create(['role' => 'Admin']);

        $section = TrainingPageSection::factory()->create();

        /** @var Collection TrainingPageContent[] */
        $contents = TrainingPageContent::factory()->times(2)->create([
            'training_page_section_id' => $section->id,
        ]);

        $video = TrainingPageContent::factory()->create([
            'training_page_section_id' => $section->id,
        ]);

        $this->assertDatabaseCount($video->getTable(), 3);

        $this->actingAs($john);

        Livewire::test(Videos::class, ['contents' => $contents, 'currentSection' => $section])
            ->assertSet('contents', $contents)
            ->assertSet('currentSection', $section)
            ->assertSet('selectedContent', new TrainingPageContent())
            ->call('onDestroy', $video)
            ->assertSet('selectedContent', $video)
            ->call('destroyVideo', $video)
            ->assertSet('selectedVideo', null)
            ->assertCount('contents', 2);

        $this->assertDatabaseCount($video->getTable(), 2);
        $this->assertDatabaseMissing($video->getTable(), $video->toArray());
    }

    /** @test */
    public function it_should_requires_admin_or_depatment_manager_role_to_delete_a_video()
    {
        $john = User::factory()->create(['role' => 'Setter']);
        $mary = User::factory()->create(['role' => 'Office Manager']);

        $section = TrainingPageSection::factory()->create();

        /** @var Collection TrainingPageContent[] */
        $contents = TrainingPageContent::factory()->times(2)->create([
            'training_page_section_id' => $section->id,
        ]);

        $video = TrainingPageContent::factory()->create([
            'training_page_section_id' => $section->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Videos::class, ['contents' => $contents, 'currentSection' => $section])
            ->call('onDestroy', $video)
            ->assertNotSet('selectedContent', $video)
            ->call('destroyVideo');

        $this->assertDatabaseHas($video->getTable(), $video->toArray());

        $this->actingAs($mary);

        Livewire::test(Videos::class, ['contents' => $contents, 'currentSection' => $section])
            ->call('onDestroy', $video)
            ->assertNotSet('selectedContent', $video)
            ->call('destroyVideo');

        $this->assertDatabaseHas($video->getTable(), $video->toArray());
    }

    /** @test */
    public function it_should_prevent_delete_the_video_if_the_user_is_not_an_admin_or_the_department_manager_that_created_the_section()
    {
        $john = User::factory()->create(['role' => 'Department Manager']);
        $mary = User::factory()->create(['role' => 'Department Manager']);

        $department = Department::factory()->create([
            'department_manager_id' => $mary->id,
        ]);

        $mary->update(['department_id' => $department->id]);

        $section = TrainingPageSection::factory()->create([
            'department_id' => $department->id,
        ]);

        /** @var Collection TrainingPageContent[] */
        $contents = TrainingPageContent::factory()->times(2)->create([
            'training_page_section_id' => $section->id,
        ]);

        $video = TrainingPageContent::factory()->create([
            'training_page_section_id' => $section->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Videos::class, ['contents' => $contents, 'currentSection' => $section])
            ->call('onDestroy', $video)
            ->assertNotSet('selectedContent', $video)
            ->call('destroyVideo');

        $this->assertDatabaseHas($video->getTable(), $video->toArray());

        $this->actingAs($mary);

        Livewire::test(Videos::class, ['contents' => $contents, 'currentSection' => $section])
            ->call('onDestroy', $video)
            ->call('destroyVideo');

        $this->assertDatabaseMissing($video->getTable(), [
            'id' => $video->id,
        ]);
    }
}
