<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SalesRepWithoutManagerId extends Notification implements ShouldQueue
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
            ->subject('User without Override Manager(s)')
            ->line(new HtmlString($this->message))
            ->action('See User', $this->path());
    }

    public function toDatabase()
    {
        return [
            'data' => [
                'message' => $this->message,
                'meta'    => [
                    'text' => 'See User',
                    'link' => $this->path(),
                ]
            ]
        ];
    }

    private function buildMessage(User $salesRep): string
    {
        $sentence = sprintf(
            "The user %s with email %s don't have the following Override Manager(s): ",
            "<strong>{$salesRep->full_name}</strong>",
            "<strong>{$salesRep->email}</strong>"
        );

        $words = collect();

        if ($salesRep->office_manager_id === null) {
            $words->push('Office Manager');
        }

        if ($salesRep->region_manager_id === null) {
            $words->push('Region Manager');
        }

        if ($salesRep->department_manager_id === null) {
            $words->push('Department Manager');
        }

        return $sentence . $words->join(', ', ', and ') . '.';
    }

    private function path(): string
    {
        return route('castle.users.show', $this->salesRep->id);
    }
}
