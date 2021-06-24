<?php

namespace Tests\Feature\FileUpload;

use App\Enum\Role;
use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\Jobs\DatabaseJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;
    
    public User $user;

    public Department $department;

    public TrainingPageSection $section;

    public Collection $files;

    protected function setUp ():void
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
        $this->withoutExceptionHandling();
        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files,
            'meta'  => [
                'training_type' => 'training'
            ]
        ]);

        $this->assertDatabaseHas('section_files', [
            'original_name' =>'avatar',
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
}
