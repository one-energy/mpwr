<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MasterExistingUserInvitation extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'description' => __('You are being invited to enter the Castle. If you choose to accept, you will have access to the Administration Area that few of us have. Be Wise!'),
            'decision'    => route('castle.masters.invite.response'),
        ];
    }
}
