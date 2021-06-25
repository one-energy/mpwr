<?php

namespace Tests\Feature\Profile;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_authenticated_to_upload_a_profile_photo()
    {
        $this->postJson(route('profile.photo-upload'), [])
            ->assertUnauthorized();
    }

    /** @test */
    public function it_should_be_possible_upload_a_profile_photo()
    {
        Storage::fake('public');

        /** @var User $john */
        $john = User::factory()->create(['role' => Role::ADMIN]);

        Storage::disk('public')->assertMissing("avatar_{$john->id}.png");

        $this->actingAs($john)
            ->postJson(route('profile.photo-upload'), [
                'photo' => UploadedFile::fake()->create('avatar.png'),
            ])
            ->assertSuccessful()
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->where('url', '/storage/profiles/avatar_1.png'));

        Storage::disk('public')->assertExists("profiles/avatar_{$john->id}.png");
    }

    /** @test */
    public function it_should_replace_the_old_photo_in_the_directory_for_the_new_one()
    {
        Storage::fake('public');

        /** @var User $john */
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $file = UploadedFile::fake()->create('avatar.png');

        $this->actingAs($john);
        $this->postJson(route('profile.photo-upload'), ['photo' => $file]);
        $this->postJson(route('profile.photo-upload'), ['photo' => $file]);

        Storage::disk('public')->assertMissing("avatar_{$john->id}.png");
        $this->assertCount(1, Storage::disk('public')->allFiles());
    }

    /** @test */
    public function it_should_prevent_upload_a_file_that_isnt_a_image()
    {
        Storage::fake('public');

        /** @var User $john */
        $john = User::factory()->create(['role' => Role::ADMIN]);

        Storage::disk('public')->assertMissing("avatar_{$john->id}.png");

        $this->actingAs($john)
            ->postJson(route('profile.photo-upload'), [
                'photo' => UploadedFile::fake()->create('avatar.pdf'),
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        Storage::disk('public')->assertMissing("avatar_{$john->id}.pdf");
    }

    /** @test */
    public function it_should_return_null_if_no_photo_provided()
    {
        Storage::fake('public');

        /** @var User $john */
        $john = User::factory()->create(['role' => Role::ADMIN]);

        Storage::disk('public')->assertMissing("avatar_{$john->id}.png");

        $this->actingAs($john)
            ->postJson(route('profile.photo-upload'), [])
            ->assertSuccessful()
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->where('url', null));

        Storage::disk('public')->assertMissing("avatar_{$john->id}.png");
    }
}
