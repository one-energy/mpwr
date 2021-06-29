<?php

namespace Tests\Feature\FileUpload;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileSectionUploadTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Department $department;

    public TrainingPageSection $section;

    public Collection $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->department = Department::factory()->create();
        $this->user       = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        $this->section = TrainingPageSection::factory()->create([
            'department_id' => $this->department->id
        ]);

        $this->files = collect([]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_save_file()
    {
        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        Storage::disk('local')->assertExists('files/' . $this->department->id . '/' . $this->files->first()->hashName());
    }

    /** @test */
    public function it_should_save_multiple_files()
    {
        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('first.pdf'));
        $this->files->push(UploadedFile::fake()->create('last.pdf'));

        $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        Storage::disk('local')->assertExists('files/' . $this->department->id . '/' . $this->files->first()->hashName());
        Storage::disk('local')->assertExists('files/' . $this->department->id . '/' . $this->files->last()->hashName());
    }

    /** @test */
    public function it_should_save_file_on_section()
    {
        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        $this->assertDatabaseHas('section_files', [
            'original_name' => 'avatar',
            'name'          => $this->files->first()->hashName(),
        ]);
    }

    /** @test */
    public function it_should_make_download_file()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('first.pdf');

        $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => [$file],
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        $response = $this->post(route('downloadSectionFile', $this->section->id), [
            'path' => 'files/' . $this->department->id . '/' . $file->hashName(),
        ]);

        $response->assertSuccessful();
    }


    /** @test */
    public function it_should_throw_error_when_not_send_files_tag()
    {
        $response = $this->post(route('uploadSectionFile', $this->section->id), [
            'meta' => [
                'training_type' => 'training'
            ]
        ]);
        $response->assertStatus(400);
    }

    /** @test */
    public function it_should_throw_error_when_not_send_training_type_tag()
    {
        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $response = $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
        ]);
        $response->assertStatus(400);
    }

    /** @test */
    public function it_shouldnt_allow_user_setter_to_upload_file()
    {
        $this->actingAs(User::factory()->create(['role' => Role::SETTER]));

        $response = $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function it_shouldnt_allow_user_sales_rep_to_upload_file()
    {
        $this->actingAs(User::factory()->create(['role' => Role::SALES_REP]));

        $response = $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function it_shouldnt_allow_user_office_manager_to_upload_file()
    {
        $this->actingAs(User::factory()->create(['role' => Role::OFFICE_MANAGER]));

        $response = $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function it_should_permit_region_manager_upload_a_file_on_self_region()
    {
        $regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);

        $region = Region::factory()->create([
            'department_id'     => $this->department->id,
            'region_manager_id' => $regionManager->id
        ]);

        $this->regionSection = TrainingPageSection::factory()->create([
            'department_id'     => $this->department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        $this->actingAs($regionManager);

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $response = $this->post(route('uploadSectionFile', $this->regionSection->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        $response->assertOk();
    }

    /** @test */
    public function it_shouldnt_permit_region_manager_upload_a_file()
    {
        $regionManagerOfSection = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $regionManager          = User::factory()->create(['role' => Role::REGION_MANAGER]);

        $region = Region::factory()->create([
            'department_id'     => $this->department->id,
            'region_manager_id' => $regionManagerOfSection->id
        ]);

        $this->regionSection = TrainingPageSection::factory()->create([
            'department_id'     => $this->department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        $this->actingAs($regionManager);

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $response = $this->post(route('uploadSectionFile', $this->regionSection->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function it_should_allow_upload_a_file_with_power_point_mime_type()
    {
        $this->withoutExceptionHandling();

        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('avatar.pptx'));

        $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
            'meta'  => ['training_type' => 'training']
        ]);

        Storage::disk('local')->assertExists('files/' . $this->department->id . '/' . $this->files->first()->hashName());
    }
}
