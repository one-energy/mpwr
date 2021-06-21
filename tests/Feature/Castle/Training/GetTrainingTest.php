<?php

namespace Tests\Feature\Castle\Training;

use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\TrainingSectionBuilder;
use Tests\TestCase;

class GetTrainingTest extends TestCase
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
        [$departmentManager, $department] = $this->createVP();

        $section = TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this
            ->actingAs($departmentManager)
            ->get(route('castle.manage-trainings.index', $department->id, $section->id))
            ->assertOk();
    }

    /** @test */
    public function it_should_show_sections()
    {
        $master = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($master);

        $section      = (new TrainingSectionBuilder)->save()->get();
        $sectionOne   = TrainingPageSection::factory()->create([
            'parent_id'     => $section->id,
            'department_id' => $department->id,
        ]);
        $sectionTwo   = TrainingPageSection::factory()->create([
            'parent_id'     => $section->id,
            'department_id' => $department->id,
        ]);
        $sectionThree = TrainingPageSection::factory()->create([
            'parent_id'     => $section->id,
            'department_id' => $department->id,
        ]);
        $sectionFour  = TrainingPageSection::factory()->create([
            'parent_id'     => $section->id,
            'department_id' => $department->id,
        ]);

        $this->actingAs($master)
            ->get(route('castle.manage-trainings.index', ['department' => $department->id, 'section' => $section->id]))
            ->assertSee($sectionOne->title)
            ->assertSee($sectionTwo->title)
            ->assertSee($sectionThree->title)
            ->assertSee($sectionFour->title);

        $this->actingAs($master)
            ->get(route('trainings.index', ['department' => $department->id, 'section' => $section->id]))
            ->assertSee($sectionOne->title)
            ->assertSee($sectionTwo->title)
            ->assertSee($sectionThree->title)
            ->assertSee($sectionFour->title);
    }

    /** @test */
    public function it_shouldnt_show_if_user_doesnt_belongs_to_departments()
    {
        /** @var Department $departmentOne */
        $departmentOne = Department::factory()->create();
        /** @var Department $departmentTwo */
        $departmentTwo = Department::factory()->create();

        $departmentManagerOne = User::factory()->create(['department_id' => $departmentOne->id]);
        $departmentManagerTwo = User::factory()->create(['department_id' => $departmentTwo->id]);

        $departmentOne->managers()->attach($departmentManagerOne->id);
        $departmentTwo->managers()->attach($departmentManagerTwo->id);

        $salesRepOne = User::factory()->create([
            'department_id' => $departmentOne->id,
            'role'          => Role::SALES_REP,
        ]);

        $salesRepTwo = User::factory()->create([
            'department_id' => $departmentTwo->id,
            'role'          => Role::SALES_REP,
        ]);

        TrainingPageSection::factory()->create([
            'parent_id'     => null,
            'department_id' => $departmentOne->id,
        ]);

        TrainingPageSection::factory()->create([
            'parent_id'     => null,
            'department_id' => $departmentTwo->id,
        ]);

        $this->actingAs($salesRepOne)
            ->get(route('trainings.index', ['department' => $salesRepOne->department_id]))
            ->assertSuccessful();

        $this->actingAs($salesRepOne)
            ->get(route('trainings.index', ['department' => $departmentTwo->id]))
            ->assertForbidden();

        $this->actingAs($salesRepTwo)
            ->get(route('trainings.index', ['department' => $salesRepTwo->department_id]))
            ->assertSuccessful();

        $this->actingAs($salesRepTwo)
            ->get(route('trainings.index', ['department' => $departmentOne->id]))
            ->assertForbidden();
    }
}
