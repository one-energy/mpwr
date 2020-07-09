<?php

namespace App\Http\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        return view('livewire.notifications', [
            'notifications' => user()->unreadNotifications()->get(),
        ]);
    }

    public function markAsRead($id)
    {
        /** @var DatabaseNotification $notification */
        $notification = user()->unreadNotifications()->where('id', $id)->firstOrFail();

        $notification->markAsRead();
    }
}
