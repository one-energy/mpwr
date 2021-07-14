<?php

namespace App\Http\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class SidebarNotifications extends Component
{
    public bool $opened = false;

    protected $listeners = [
        'toggleSidebar',
    ];

    public function render()
    {
        return view('livewire.sidebar-notifications')->with([
            'notifications' => user()->unreadNotifications()->get(),
        ]);
    }

    public function getMessage($notification)
    {
        return $notification['data']['data']['message'];
    }

    public function hasMeta($notification)
    {
        return !empty($notification['data']['data']['meta']);
    }

    public function read($notification)
    {
        /** @var DatabaseNotification $notification */
        $notification = user()->unreadNotifications()->where('id', $notification['id'])->firstOrFail();

        $notification->markAsRead();

        $hasUnreadNotifications = user()->unreadNotifications()->count();

        $this->dispatchBrowserEvent('has-unread-notifications', [
            'payload' => $hasUnreadNotifications > 0 ? 'true' : 'false',
        ]);
    }

    public function toggleSidebar()
    {
        $this->opened = !$this->opened;
    }
}
