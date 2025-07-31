<?php

namespace App\Notifications\DatabaseNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FavoriteNotification extends Notification
{
    use Queueable;

    protected $favoriter;

    public function __construct($favoriter)
    {
        $this->favoriter = $favoriter;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'favorite',
            'title' => $this->favoriter->name . ' added you to favorites!',
            'body' => 'Someone is interested in your profile. Tap to view!',
            'favoriter_id' => $this->favoriter->id,
            'favoriter_name' => $this->favoriter->name,
            'favoriter_avatar' => $this->favoriter->avatar ?? null,
            'action_url' => '/profile/' . $this->favoriter->id,
            'created_at' => now()->toISOString(),
        ];
    }
} 