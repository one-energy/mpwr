<?php

namespace App\View\Components;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\Component;

class Notification extends Component
{
    private DatabaseNotification $notification;

    public function __construct(DatabaseNotification $notification)
    {
        $this->notification = $notification;
    }

    public function description()
    {
        if (isset($this->notification->data['description'])) {
            return $this->notification->data['description'];
        }

        return '';
    }

    public function icon()
    {
        if (isset($this->notification->data['icon'])) {
            return $this->notification->data['icon'];
        }

        return 'alert';
    }

    public function id()
    {
        return $this->notification->id;
    }

    public function decisionUrl()
    {
        return $this->notification->data['decision'] ?? '';
    }

    public function render()
    {
        return view('components.notification');
    }

    public function hasDecision()
    {
        return isset($this->notification->data['decision']);
    }
}
