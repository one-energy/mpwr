<?php

namespace Tests\Feature\Castle\ManageTraining;

use App\Enum\Role;
use App\Models\TrainingPageContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateTrainingContentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => Role::ADMIN]);
    }

    /** @test */
    public function it_should_update_training_section()
    {
        /** @var TrainingPageContent $content */
        $content = TrainingPageContent::factory()->create();

        $newTitle       = Str::random();
        $content->title = $newTitle;

        $this->updateContent($content)
            ->assertSessionHas('alert')
            ->assertRedirect(route('castle.manage-trainings.index', [
                'department' => $content->section->department_id,
                'section'    => $content->training_page_section_id,
            ]));

        $this->assertDatabaseHas($content->getTable(), [
            'id'    => $content->id,
            'title' => $newTitle
        ]);
    }

    /** @test */
    public function it_should_require_title()
    {
        /** @var TrainingPageContent $content */
        $content = TrainingPageContent::factory()->create();

        $content->title = null;

        $this->updateContent($content)->assertSessionHasErrors('title');

        $content->refresh();

        $this->assertDatabaseHas($content->getTable(), [
            'id'    => $content->id,
            'title' => $content->title
        ]);
    }

    /** @test */
    public function it_should_require_video_url()
    {
        /** @var TrainingPageContent $content */
        $content = TrainingPageContent::factory()->create();

        $content->video_url = null;

        $this->updateContent($content)->assertSessionHasErrors('video_url');

        $content->refresh();

        $this->assertDatabaseHas($content->getTable(), [
            'id'        => $content->id,
            'video_url' => $content->video_url
        ]);
    }

    /** @test */
    public function it_should_require_description()
    {
        /** @var TrainingPageContent $content */
        $content = TrainingPageContent::factory()->create();

        $content->description = null;

        $this->updateContent($content)->assertSessionHasErrors('description');

        $content->refresh();

        $this->assertDatabaseHas($content->getTable(), [
            'id'          => $content->id,
            'description' => $content->description
        ]);
    }

    /** @test */
    public function it_should_prevent_title_greater_than_255_characters()
    {
        /** @var TrainingPageContent $content */
        $content = TrainingPageContent::factory()->create();

        $content->title = Str::random(256);

        $this->updateContent($content)->assertSessionHasErrors('title');

        $content->refresh();

        $this->assertDatabaseHas($content->getTable(), [
            'id'    => $content->id,
            'title' => $content->title
        ]);
    }

    /** @test */
    public function it_should_prevent_video_url_greater_than_255_characters()
    {
        /** @var TrainingPageContent $content */
        $content = TrainingPageContent::factory()->create();

        $content->video_url = Str::random(256);

        $this->updateContent($content)->assertSessionHasErrors('video_url');

        $content->refresh();

        $this->assertDatabaseHas($content->getTable(), [
            'id'        => $content->id,
            'video_url' => $content->video_url
        ]);
    }

    private function updateContent(TrainingPageContent $content, ?User $user = null): TestResponse
    {
        $user = $user ?? $this->admin;

        return $this->actingAs($user)
            ->post(
                route('castle.manage-trainings.updateContent', ['content' => $content]),
                $content->toArray()
            );
    }
}
