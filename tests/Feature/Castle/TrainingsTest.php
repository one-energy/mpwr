<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
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

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_section_index()
    {
        $departmentManager = User::factory()->create(["role" => "Department Manager"]);
        $department = Department::factory()->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();
        $section = TrainingPageSection::factory()->create(["department_id" => $department->id]);

        $this->actingAs($departmentManager)
            ->get(route('castle.manage-trainings.index', $department->id, $section->id))
            ->assertStatus(200);

    }

    /** @test */
    public function it_should_show_sections()
    {
        $master = User::factory()->create([
            "role" => "Admin"
        ]);
        $department = Department::factory()->create([
            "department_manager_id" => $master->id
        ]);
        $section = (new TrainingSectionBuilder)->save()->get();
        $sectionOne = TrainingPageSection::factory()->create([
            'parent_id' => $section->id,
            'department_id' => $department->id
        ]);
        $sectionTwo = TrainingPageSection::factory()->create([
            'parent_id' => $section->id,
            'department_id' => $department->id
        ]);
        $sectionThree = TrainingPageSection::factory()->create([
            'parent_id' => $section->id,
            'department_id' => $department->id
        ]);
        $sectionFour = TrainingPageSection::factory()->create([
            'parent_id' => $section->id,
            'department_id' => $department->id
        ]);

        // print_r($sectionOne);
        $this->actingAs($master)
            ->get(route('castle.manage-trainings.index', ["department" => $department->id, "section" => $section->id]))
            ->assertSee($sectionOne->title)
            ->assertSee($sectionTwo->title)
            ->assertSee($sectionThree->title)
            ->assertSee($sectionFour->title);

        $this->actingAs($master)
            ->get(route('trainings.index', ["department" => $department->id, "section" => $section->id]))
            ->assertSee($sectionOne->title)
            ->assertSee($sectionTwo->title)
            ->assertSee($sectionThree->title)
            ->assertSee($sectionFour->title);
    }

    /** @test */
    public function it_should_show_content_of_section()
    {
        $departmentManager = User::factory()->create([
            "role" => "Department Manager"
        ]);
        $department = Department::factory()->create([
            "department_manager_id" => $departmentManager->id
        ]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $section = TrainingPageSection::factory()->create([
            "department_id" => $department->id
        ]);
        $content = TrainingPageContent::factory()->create([
            'training_page_section_id' => $section->id
        ]);

        $this->actingAs($departmentManager)
            ->get(route('castle.manage-trainings.index', $section->id))
            ->assertSee($content->description)
            ->assertSee($content->title);

        $this->actingAs($departmentManager)
            ->get(route('trainings.index', $section->id))
            ->assertSee($content->description)
            ->assertSee($content->title);
    }

    /** @test */
    public function it_should_delete_a_section()
    {
        $departmentManager = User::factory()->create([
            "role" => "Department Manager"
        ]);

        $department = Department::factory()->create([
            "department_manager_id" => $departmentManager->id
        ]);

        $departmentManager->department_id = $department->id;
        $department->save();

        $section = TrainingPageSection::factory()->create([
            'parent_id' => null,
            'department_id' => $department->id
        ]);
        $sectionOne = TrainingPageSection::factory()->create([
            'parent_id' => $section->id,
            'department_id' => $department->id
        ]);

        $this->actingAs($departmentManager)
            ->get(route('castle.manage-trainings.index', $section->id))
            ->assertSee($sectionOne->title);

        $this->actingAs($departmentManager)
            ->delete(route('castle.manage-trainings.deleteSection', $sectionOne->id))
            ->assertDontSee($sectionOne->title);
    }

    /** @test */
    public function it_shouldnt_show_if_user_doesnt_belongs_to_departments()
    {
        $departmentOne = Department::factory()->create();
        $departmentTwo = Department::factory()->create();

        $departmentManagerOne = User::factory()->create([
            "department_id" => $departmentOne->id
        ]);
        $departmentManagerTwo = User::factory()->create([
            "department_id" => $departmentTwo->id
        ]);

        $departmentOne->department_manager_id = $departmentManagerOne->id;
        $departmentTwo->department_manager_id = $departmentManagerTwo->id;

        $departmentOne->save();
        $departmentTwo->save();

        $salesRepOne = User::factory()->create([
            "department_id" => $departmentOne->id,
            "role"          => "Sales Rep"
        ]);

        $salesRepTwo = User::factory()->create([
            "department_id" => $departmentTwo->id,
            "role"          => "Sales Rep"
        ]);

        $sectionOne = TrainingPageSection::factory()->create([
            'parent_id' => null,
            'department_id' => $departmentOne->id
        ]);

        $sectionTwo = TrainingPageSection::factory()->create([
            'parent_id' => null,
            'department_id' => $departmentTwo->id
        ]);

        $this->actingAs($salesRepOne)
            ->get(route('trainings.index', [
                "department" => $salesRepOne->department_id
                ])
            )->assertSuccessful();

        $this->actingAs($salesRepOne)
            ->get(route('trainings.index', [
                "department" => $departmentTwo->id
                ])
            )->assertForbidden();

        $this->actingAs($salesRepTwo)
            ->get(route('trainings.index', [
                "department" => $salesRepTwo->department_id
                ])
            )->assertSuccessful();

        $this->actingAs($salesRepTwo)
            ->get(route('trainings.index', [
                "department" => $departmentOne->id
                ])
            )->assertForbidden();
    }
}
