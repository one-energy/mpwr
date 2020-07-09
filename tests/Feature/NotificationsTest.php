<?php

namespace Tests\Feature;

use App\Http\Livewire\Notifications;
use App\Notifications\MasterExistingUserInvitation;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Livewire;
use Tests\Builders\NotificationBuilder;
use Tests\Builders\UserBuilder;

class NotificationsTest extends FeatureTest
{
    /** @test */
    public function it_should_show_notification_for_the_user()
    {
        $user = (new UserBuilder)->save()->get();

        (new NotificationBuilder)
            ->notification(new MasterExistingUserInvitation)
            ->for($user)
            ->with([
                'description' => 'You have been notified!',
            ])
            ->save();

        $this->actingAs($user);

        Livewire::test(Notifications::class)
            ->assertSee('You have been notified!');
    }

    /** @test */
    public function it_should_be_able_to_mark_the_notification_as_read()
    {
        $user = (new UserBuilder)->save()->get();

        (new NotificationBuilder)
            ->notification(new MasterExistingUserInvitation)
            ->for($user)
            ->unread()
            ->with([
                'description' => 'You have been notified!',
                'icon'        => 'shield',
            ])
            ->save();

        $this->actingAs($user);

        $notification = DatabaseNotification::query()->first();

        Livewire::test(Notifications::class)
            ->call('markAsRead', $notification->id);

        $notification->refresh();

        $this->assertNotNull($notification->read_at);
    }
}
