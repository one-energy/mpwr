<?php

namespace Tests\Feature\FileUpload;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
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
        $this->section    = TrainingPageSection::factory()->create(['department_id' => $this->department->id]);
        $this->files      = collect([]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_save_file()
    {
        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->uploadFile()->assertSuccessful()->assertOk();

        Storage::disk('local')->assertExists($this->getFilePath($this->files->first()->hashName()));
    }

    /** @test */
    public function it_should_save_multiple_files()
    {
        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('first.pdf'));
        $this->files->push(UploadedFile::fake()->create('last.pdf'));

        $this->uploadFile()->assertSuccessful()->assertOk();

        Storage::disk('local')->assertExists($this->getFilePath($this->files->first()->hashName()));
        Storage::disk('local')->assertExists($this->getFilePath($this->files->last()->hashName()));
    }

    /** @test */
    public function it_should_save_file_on_section()
    {
        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->uploadFile()->assertSuccessful()->assertOk();

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

        $this->uploadFile($this->section, [
            'files' => [$file],
            'meta'  => ['training_type' => 'training']
        ])
            ->assertSuccessful()
            ->assertOk();

        $this->post(route('downloadSectionFile', $this->section->id), [
            'path' => 'files/' . $this->department->id . '/' . $file->hashName(),
        ])
            ->assertSuccessful()
            ->assertOk();
    }


    /** @test */
    public function it_should_throw_error_when_not_send_files_tag()
    {
        $this->uploadFile($this->section, ['meta' => ['training_type' => 'training']])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_should_throw_error_when_not_send_training_type_tag()
    {
        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->uploadFile($this->section, ['files' => $this->files->toArray()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_shouldnt_allow_user_setter_to_upload_file()
    {
        $this->actingAs(User::factory()->create(['role' => Role::SETTER]))
            ->uploadFile()
            ->assertForbidden();
    }

    /** @test */
    public function it_shouldnt_allow_user_sales_rep_to_upload_file()
    {
        $this->actingAs(User::factory()->create(['role' => Role::SALES_REP]))
            ->uploadFile()
            ->assertForbidden();
    }

    /** @test */
    public function it_shouldnt_allow_user_office_manager_to_upload_file()
    {
        $this->actingAs(User::factory()->create(['role' => Role::OFFICE_MANAGER]))
            ->uploadFile()
            ->assertForbidden();
    }

    /** @test */
    public function it_should_permit_region_manager_upload_a_file_on_self_region()
    {
        $regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create(['department_id' => $this->department->id]);
        $region->managers()->attach($regionManager->id);

        $regionSection = TrainingPageSection::factory()->create([
            'department_id'     => $this->department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->actingAs($regionManager)
            ->uploadFile($regionSection)
            ->assertSuccessful()
            ->assertOk();
    }

    /** @test */
    public function it_shouldnt_permit_region_manager_upload_a_file()
    {
        $regionManagerOfSection = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $regionManager          = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Region $region */
        $region = Region::factory()->create(['department_id' => $this->department->id]);
        $region->managers()->attach($regionManagerOfSection->id);

        $regionSection = TrainingPageSection::factory()->create([
            'department_id'     => $this->department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->actingAs($regionManager)
            ->uploadFile($regionSection)
            ->assertForbidden();
    }

    /** @test */
    public function it_should_allow_upload_a_file_with_power_point_mime_type()
    {
        Storage::fake('local');

        $this->files->push(
            UploadedFile::fake()->create('avatar.pptx', 100, 'application/vnd.ms-powerpoint')
        );

        $this->uploadFile()->assertSuccessful()->assertOk();

        Storage::disk('local')->assertExists($this->getFilePath($this->files->last()->hashName()));
    }

    private function uploadFile(?TrainingPageSection $section = null, array $attributes = [])
    {
        $section    = $section ?? $this->section;
        $attributes = collect($attributes)->isEmpty() ? $this->defaultAttributes() : $attributes;

        return $this->postJson(route('uploadSectionFile', $section->id), $attributes);
    }

    private function defaultAttributes()
    {
        return [
            'files' => $this->files->toArray(),
            'meta'  => ['training_type' => 'training']
        ];
    }

    private function getFilePath(string $hashName, ?Department $department = null): string
    {
        $department = $department ?? $this->department;

        return sprintf('files/%s/%s', $department->id, $hashName);
    }
}
