<?php


namespace Tests\Builders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class NotificationBuilder
{
    use WithFaker;

    private string $type;
    private string $notifiable_type;
    private int $notifiable_id;
    private array $data = [];
    private $read_at;
    private Carbon $created_at;
    private Carbon $updated_at;

    public function __construct($attributes = [])
    {
        $this->faker      = $this->makeFaker('en_US');
        $this->created_at = now();
        $this->updated_at = now();
    }

    public function for(Model $model)
    {
        $this->notifiable_type = get_class($model);
        $this->notifiable_id   = $model->id;

        return $this;
    }

    public function notification(Notification $notification)
    {
        $this->type = get_class($notification);

        return $this;
    }

    public function save(): DatabaseNotification
    {
        /** @var DatabaseNotification $notification */
        $notification = DatabaseNotification::query()->create([
            'id'              => Str::uuid(),
            'type'            => $this->type,
            'notifiable_type' => $this->notifiable_type,
            'notifiable_id'   => $this->notifiable_id,
            'data'            => $this->data,
            'read_at'         => $this->read_at,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ]);

        return $notification;
    }

    public function with(array $array)
    {
        $this->data = $array;

        return $this;
    }

    public function unread()
    {
        $this->read_at = null;

        return $this;
    }
}
