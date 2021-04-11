<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings;

use App\Http\Livewire\Castle\ManageTrainings\Videos;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_see_videos_info()
    {
        $section = TrainingPageSection::factory()->create();

        /** @var Collection[TrainingPageContent] */
        $contents = TrainingPageContent::factory()->times(2)->create([
            'training_page_section_id' => $section->id,
            'description'              => Str::random('50'),
        ]);

        Livewire::test(Videos::class, ['contents' => $contents, 'currentSection' => $section])
            ->assertSet('contents', $contents)
            ->assertSet('currentSection', $section)
            ->assertSee($contents[0]->title)
            ->assertSee($contents[0]->description)
            ->assertSee($contents[1]->title)
            ->assertSee($contents[1]->description);
    }

    /** @test */
    public function it_should_be_possible_delete_a_video()
    {
        $section = TrainingPageSection::factory()->create();

        /** @var Collection[TrainingPageContent] */
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
            ->assertSet('selectedContent', null)
            ->call('onDestroy', $video)
            ->assertSet('selectedContent', $video)
            ->call('destroyVideo', $video)
            ->assertSet('selectedVideo', null)
            ->assertCount('contents', 2);

        $this->assertDatabaseCount($video->getTable(), 2);
        $this->assertDatabaseMissing($video->getTable(), $video->toArray());
    }
}
