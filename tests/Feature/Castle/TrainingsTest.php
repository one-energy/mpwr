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

        $this->user = factory(User::class)->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_show_section_index()
    {
        $departmentManager = factory(User::class)->create(["role" => "Department Manager"]);
        $department = factory(Department::class)->create(["department_manager_id" => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();
        $section = factory(TrainingPageSection::class)->create(["department_id" => $department->id]);

        $this->actingAs($departmentManager)
            ->get(route('castle.manage-trainings.index', $department->id, $section->id))
            ->assertStatus(200);

    }

    /** @test */
    public function it_should_show_sections()
    {
        $master = factory(User::class)->create([
            "role" => "Admin"
        ]);
        $department = factory(Department::class)->create([
            "department_manager_id" => $master->id
        ]);
        $section = (new TrainingSectionBuilder)->save()->get();
        $sectionOne = factory(TrainingPageSection::class)->create([
            'parent_id' => $section->id,
            'department_id' => $department->id
        ]);
        $sectionTwo = factory(TrainingPageSection::class)->create([
            'parent_id' => $section->id,
            'department_id' => $department->id
        ]);
        $sectionThree = factory(TrainingPageSection::class)->create([
            'parent_id' => $section->id,
            'department_id' => $department->id
        ]);
        $sectionFour = factory(TrainingPageSection::class)->create([
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
        $departmentManager = factory(User::class)->create([
            "role" => "Department Manager"
        ]);
        $department = factory(Department::class)->create([
            "department_manager_id" => $departmentManager->id
        ]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $section = factory(TrainingPageSection::class)->create([
            "department_id" => $department->id
        ]);
        $content = factory(TrainingPageContent::class)->create([
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
        $departmentManager = factory(User::class)->create([
            "role" => "Department Manager"
        ]);
        
        $department = factory(Department::class)->create([
            "department_manager_id" => $departmentManager->id
        ]);

        $departmentManager->department_id = $department->id;
        $department->save();

        $section = factory(TrainingPageSection::class)->create([
            'parent_id' => null,
            'department_id' => $department->id
        ]);
        $sectionOne = factory(TrainingPageSection::class)->create([
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
        $departmentOne = factory(Department::class)->create();
        $departmentTwo = factory(Department::class)->create();
        
        $departmentManagerOne = factory(User::class)->create([
            "department_id" => $departmentOne->id
        ]);
        $departmentManagerTwo = factory(User::class)->create([
            "department_id" => $departmentTwo->id
        ]);

        $departmentOne->department_manager_id = $departmentManagerOne->id;
        $departmentTwo->department_manager_id = $departmentManagerTwo->id;
        
        $departmentOne->save();
        $departmentTwo->save();

        $salesRepOne = factory(User::class)->create([
            "department_id" => $departmentOne->id,
            "role"          => "Sales Rep"
        ]);

        $salesRepTwo = factory(User::class)->create([
            "department_id" => $departmentTwo->id,
            "role"          => "Sales Rep"
        ]);

        $sectionOne = factory(TrainingPageSection::class)->create([
            'parent_id' => null,
            'department_id' => $departmentOne->id
        ]);

        $sectionTwo = factory(TrainingPageSection::class)->create([
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
