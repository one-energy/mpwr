<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SalesRepWithoutManagerId extends Notification
{
    use Queueable;

    private string $message;

    private User $salesRep;

    public function __construct(User $salesRep)
    {
        $this->salesRep = $salesRep;
        $this->message  = $this->buildMessage($salesRep);
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('User without manager(s)')
            ->line(new HtmlString($this->message));
    }

    public function toDatabase()
    {
        return [
            'data' => [
                'message' => $this->message,
                'meta'    => [
                    'text' => 'See User',
                    'link' => route('castle.users.show', $this->salesRep->id),
                ]
            ]
        ];
    }

    private function buildMessage(User $salesRep): string
    {
        $sentence = sprintf(
            "The user %s with email %s don't have ",
            "<strong>{$salesRep->full_name}</strong>",
            "<strong>{$salesRep->email}</strong>"
        );

        $words = collect();

        if ($salesRep->office_manager_id === null) {
            $words->push('an Office Manager');
        }

        if ($salesRep->region_manager_id === null) {
            $words->push('a Region Manager');
        }

        if ($salesRep->department_manager_id === null) {
            $words->push('a Department Manager');
        }

        return $sentence . $words->join(', ', ', and ') . '.';
    }
}
