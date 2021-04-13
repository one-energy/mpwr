<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ListFiles;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ListFilesTest extends TestCase
{

    use RefreshDatabase;

    private TrainingPageSection $section;
    private Collection $files;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'Admin']));
        $this->section = TrainingPageSection::factory()->create();

        Storage::fake('local');
        $fileOne = UploadedFile::fake()->create('testeOne');
        $path = $fileOne->store('files/' . $this->section->department_id);
        $this->files = collect([
            $this->section->files()->create([
                'name'          => $fileOne->hashName(),
                'original_name' => Str::of($fileOne->getClientOriginalName())->beforeLast('.'),
                'size'          => $fileOne->getSize(),
                'type'          => $fileOne->extension(),
                'path'          => $path,
            ])
        ]);
    }

    /** @test */
    public function it_should_show_files_list()
    {
        Livewire::test(ListFiles::class,['files' => $this->files])
            ->assertSee('testeOne');
    }
}
