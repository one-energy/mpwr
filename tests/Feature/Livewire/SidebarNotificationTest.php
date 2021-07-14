<?php

namespace Tests\Feature\Livewire;

use App\Enum\Role;
use App\Http\Livewire\SidebarNotifications;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class SidebarNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $john;

    protected function setUp(): void
    {
        parent::setUp();

        $this->john = User::factory()->create(['role' => Role::ADMIN]);
    }

    /** @test */
    public function it_should_be_possible_toggle()
    {
        $this->actingAs($this->john);

        Livewire::test(SidebarNotifications::class)
            ->assertSet('opened', false)
            ->call('toggleSidebar')
            ->assertSet('opened', true)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_get_the_message_from_notification()
    {
        $this->createNotification($this->john);

        $notification = $this->john->unreadNotifications()->first();

        $this->actingAs($this->john);

        $component = Livewire::test(SidebarNotifications::class)->call('getMessage', $notification);

        $this->assertEquals(
            $notification['data']['data']['message'],
            $component->payload['effects']['returns']['getMessage']
        );
    }

    /** @test */
    public function it_should_return_true_if_notification_has_meta()
    {
        $this->createNotification($this->john);

        $notification = $this->john->unreadNotifications()->first();

        $this->actingAs($this->john);

        $component = Livewire::test(SidebarNotifications::class)->call('hasMeta', $notification);

        $this->assertTrue($component->payload['effects']['returns']['hasMeta']);
    }

    /** @test */
    public function it_should_be_possible_read_a_message()
    {
        $this->createNotification($this->john);

        $notification = $this->john->unreadNotifications()->first();

        $this->actingAs($this->john);

        $this->assertCount(1, $this->john->unreadNotifications()->get());

        Livewire::test(SidebarNotifications::class)
            ->call('read', $notification)
            ->assertDispatchedBrowserEvent('has-unread-notifications');

        $this->assertCount(0, $this->john->unreadNotifications()->get());
    }

    private function createNotification(?User $user = null): void
    {
        $user = $user ?? $this->john;

        DB::table('notifications')
            ->insert([
                'id'              => Str::random(),
                'type'            => Str::random(),
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => json_encode([
                    'data' => [
                        'message' => Str::random(),
                        'meta'    => [
                            'text' => 'See User',
                            'link' => 'dummy',
                        ]
                    ]
                ], JSON_THROW_ON_ERROR)
            ]);
    }
}
