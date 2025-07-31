<?php

namespace App\Notifications\DatabaseNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MatchNotification extends Notification
{
    use Queueable;

    protected $matchedUser;

    public function __construct($matchedUser)
    {
        $this->matchedUser = $matchedUser;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'match',
            'title' => 'New Match! 🎉',
            'body' => 'You matched with ' . $this->matchedUser->name . '! Start a conversation now.',
            'matched_user_id' => $this->matchedUser->id,
            'matched_user_name' => $this->matchedUser->name,
            'matched_user_avatar' => $this->matchedUser->avatar ?? null,
            'action_url' => '/chat/start/' . $this->matchedUser->id,
            'created_at' => now()->toISOString(),
        ];
    }
} 