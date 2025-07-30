<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification
{
    use Queueable;

    //title and body can be set dynamically
    protected $title;
    protected $body;
    protected $image;
    /**
     * Create a new notification instance.
     */
    public function __construct($title, $body = '', $image = '')
    {
        $this->title = $title;
        $this->body = $body ?? '';
        $this->image = $image ?? '';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['fcm'];
    }
    /**
     * Get the FCM representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toFcm($notifiable)
    {
        return [
            'to' => $notifiable->device_token,
            'notification' => [
                'title'    => $this->title,
                'body'     => $this->body,
                'image'    => $this->image,
            ],
        ];
    }

}
