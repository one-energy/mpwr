<?php

namespace Tests\Feature\FileUpload;

use App\Enum\Role;
use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;
    public User $user;
    public Collection $files;

    protected function setUp ():void
    {
        parent::setUp();

        $this->userEniumPoints = collect([]);

        $this->user = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $this->files = collect([]);
    }

    /** @test */
    public function it_should_save_file()
    {
        $this->actingAs($this->user);

        Storage::fake('local');

        $department = Department::factory()->create();

        $section = TrainingPageSection::factory()->create([
            'department_id' => $department->id
        ]);

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->post(route('uploadSectionFile', $section->id), [
            'files' => $this->files,
        ]);

        Storage::disk('local')->assertExists('files/' . $department->id . '/' . $this->files->first()->hashName());
    }

     /** @test */
     public function it_should_save_multiple_files()
     {
         $this->withoutExceptionHandling();

         $this->actingAs($this->user);
 
         Storage::fake('local');
 
         $department = Department::factory()->create();
 
         $section = TrainingPageSection::factory()->create([
             'department_id' => $department->id
         ]);
 
         $this->files->push(UploadedFile::fake()->create('first.pdf'));
         $this->files->push(UploadedFile::fake()->create('last.pdf'));
 
         $this->post(route('uploadSectionFile', $section->id), [
             'files' => $this->files,
             'meta'  => [
                 'training_type' => 'training'
             ]
         ]);
 
         Storage::disk('local')->assertExists('files/' . $department->id . '/' . $this->files->first()->hashName());
         Storage::disk('local')->assertExists('files/' . $department->id . '/' . $this->files->last()->hashName());
     }
}
