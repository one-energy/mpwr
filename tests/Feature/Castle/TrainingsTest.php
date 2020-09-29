<?php

namespace Tests\Feature\Castle;

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\TrainingSectionBuilder;
use Tests\Builders\UserBuilder;
use Tests\TestCase;

class TrainingsTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_section_index()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $section = (new TrainingSectionBuilder)->save()->get();

        $this->actingAs($master)
            ->get(route('castle.manage-trainings.index', $section->id))
            ->assertStatus(200);

    }

    /** @test */
    public function it_should_show_sections()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $section = (new TrainingSectionBuilder)->save()->get();
        $sectionOne = factory(TrainingPageSection::class)->create([
            'parent_id' => $section->id
        ]);
        $sectionTwo = factory(TrainingPageSection::class)->create([
            'parent_id' => $section->id
        ]);
        $sectionThree = factory(TrainingPageSection::class)->create([
            'parent_id' => $section->id
        ]);
        $sectionFour = factory(TrainingPageSection::class)->create([
            'parent_id' => $section->id
        ]);

        // print_r($sectionOne);
        $this->actingAs($master)
            ->get(route('castle.manage-trainings.index', $section->id))
            ->assertSee($sectionOne->title)
            ->assertSee($sectionTwo->title)
            ->assertSee($sectionThree->title)
            ->assertSee($sectionFour->title);

        $this->actingAs($master)
            ->get(route('trainings.index', $section->id))
            ->assertSee($sectionOne->title)
            ->assertSee($sectionTwo->title)
            ->assertSee($sectionThree->title)
            ->assertSee($sectionFour->title);
    }

    /** @test */
    public function it_should_show_content_of_section()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $section = (new TrainingSectionBuilder)->save()->get();
        $content = factory(TrainingPageContent::class)->create([
            'training_page_section_id' => $section->id
        ]);
    
        $this->actingAs($master)
            ->get(route('castle.manage-trainings.index', $section->id))
            ->assertSee($content->description)
            ->assertSee($content->title);

        $this->actingAs($master)
            ->get(route('trainings.index', $section->id))
            ->assertSee($content->description)
            ->assertSee($content->title);
    }

    /** @test */
    public function it_should_delete_a_section()
    {
        $master = (new UserBuilder)->asMaster()->save()->get();
        $section = factory(TrainingPageSection::class)->create([
            'parent_id' => null
        ]);
        $sectionOne = factory(TrainingPageSection::class)->create([
            'parent_id' => $section->id
        ]);

        $this->actingAs($master)
            ->get(route('castle.manage-trainings.index', $section->id))
            ->assertSee($sectionOne->title);
        
        $this->actingAs($master)
            ->delete(route('castle.manage-trainings.deleteSection', $sectionOne->id))
            ->assertDontSee($sectionOne->title);
    }
}
