<?php

namespace Tests\Feature\ListFiles;

use App\Enum\Role;
use App\Http\Livewire\ListFiles;
use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ListFilesTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Department $department;

    public TrainingPageSection $section;

    public Collection $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->files = collect([]);

        $this->department = Department::factory()->create();

        $this->user = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        $this->section = TrainingPageSection::factory()->create([
            'department_id' => $this->department->id,
        ]);

        $this->actingAs($this->user);

        Storage::fake('local');

        $this->files->push(UploadedFile::fake()->create('avatar.pdf'));

        $this->post(route('uploadSectionFile', $this->section->id), [
            'files' => $this->files->toArray(),
            'meta'  => ['training_type' => 'training'],
        ]);
    }

    /** @test */
    public function it_should_list_files()
    {
        Livewire::test(ListFiles::class, [
            'files'            => $this->section->files,
            'showDeleteButton' => false,
        ])
            ->assertSee('avatar');
    }

    /** @test */
    public function it_should_delete_files()
    {
        Livewire::test(ListFiles::class, [
            'files'            => $this->section->files,
            'showDeleteButton' => true,
        ])
            ->assertSee('avatar')
            ->call('onDestroy', $this->section->files()->first())
            ->call('removeFile')
            ->assertDontSee('avatar');
    }

    /** @test */
    public function it_shouldnt_show_delete_button()
    {
        Livewire::test(ListFiles::class, [
            'files'            => $this->section->files,
            'showDeleteButton' => false,
        ])
            ->assertDontSeeHtml('<x-svg.trash class="w-5 h-5  text-red-600 fill-current" />');
    }

    /** @test */
    public function it_should_download_file()
    {
        Livewire::test(ListFiles::class, [
            'files'            => $this->section->files,
            'showDeleteButton' => false,
        ])
            ->call('downloadSectionFile', $this->section->files->first())
            ->assertDispatchedBrowserEvent('show-alert');
    }
}
