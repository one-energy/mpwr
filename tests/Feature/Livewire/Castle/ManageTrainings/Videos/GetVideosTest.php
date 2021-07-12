<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings\Videos;

use App\Http\Livewire\Castle\ManageTrainings\Videos;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class GetVideosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_see_videos_info()
    {
        $section = TrainingPageSection::factory()->create();

        /** @var Collection TrainingPageContent[] */
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
    public function it_should_be_possible_call_open_show_video_modal_without_errors()
    {
        $section = TrainingPageSection::factory()->create();

        /** @var Collection TrainingPageContent[] */
        $contents = TrainingPageContent::factory()->times(2)->create([
            'training_page_section_id' => $section->id,
            'description'              => Str::random('50'),
        ]);

        Livewire::test(Videos::class, [
            'contents'       => $contents,
            'currentSection' => $section
        ])
            ->assertSet('showVideoModal', false)
            ->assertSet('selectedContent', new TrainingPageContent())
            ->call('openShowVideoModal', $contents->first())
            ->assertSet('showVideoModal', true)
            ->assertSet('selectedContent', $contents->first())
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_call_on_edit_without_errors()
    {
        $section = TrainingPageSection::factory()->create();

        /** @var Collection TrainingPageContent[] */
        $contents = TrainingPageContent::factory()->times(2)->create([
            'training_page_section_id' => $section->id,
            'description'              => Str::random('50'),
        ]);

        Livewire::test(Videos::class, [
            'contents'       => $contents,
            'currentSection' => $section
        ])
            ->assertSet('showEditVideoModal', false)
            ->assertSet('selectedContent', new TrainingPageContent())
            ->assertSet('updateRoute', '')
            ->call('onEdit', $contents->first())
            ->assertSet('showEditVideoModal', true)
            ->assertSet('selectedContent', $contents->first())
            ->assertSet('updateRoute', route('castle.manage-trainings.updateContent', $contents->first()->id))
            ->assertHasNoErrors();
    }
}
