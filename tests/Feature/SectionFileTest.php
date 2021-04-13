<?php

namespace Tests\Feature;

use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SectionFileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_upload_a_file()
    {
        $this->actingAs(User::factory()->create(['role' => 'Admin']));

        Storage::fake('local');
        $file = UploadedFile::fake()->create('testeOne');
        $section = TrainingPageSection::factory()->create();
        $response = $this->post(route('uploadSectionFile', $section), [
            'files' => [
                $file
            ]
        ]);
        Storage::disk('local')->assertExists('/files/' . $section->department_id . '/' . $file->hashName());
    }

    /** @test */
    public function it_should_store_a_section_file()
    {
        $this->actingAs(User::factory()->create(['role' => 'Admin']));

        Storage::fake('local');
        $file = UploadedFile::fake()->create('testeOne');
        $section = TrainingPageSection::factory()->create();
        $response = $this->post(route('uploadSectionFile', $section), [
            'files' => [
                $file
            ]
        ]);
        $this->assertDatabaseHas('section_files', [
            'name' => $file->hashName()
        ]);
    }

}
