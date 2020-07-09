<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MasterInvitation extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(Invitation $notifiable)
    {
        return (new MailMessage)
            ->line(__('You have been invited to get into the Castle of ') . config('app.name') . '.')
            ->action(__('Accept Invitation'), $notifiable->path());
    }
}
