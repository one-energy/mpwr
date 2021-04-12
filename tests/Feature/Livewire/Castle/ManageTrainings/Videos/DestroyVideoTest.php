<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings\Videos;

use App\Http\Livewire\Castle\ManageTrainings\Videos;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
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
        $section = TrainingPageSection::factory()->create();

        /** @var Collection TrainingPageContent[] */
        $contents = TrainingPageContent::factory()->times(2)->create([
            'training_page_section_id' => $section->id,
        ]);

        $video = TrainingPageContent::factory()->create([
            'training_page_section_id' => $section->id,
        ]);

        $this->assertDatabaseCount($video->getTable(), 3);

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
}
