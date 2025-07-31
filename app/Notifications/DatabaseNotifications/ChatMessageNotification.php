<?php

namespace App\Notifications\DatabaseNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatMessageNotification extends Notification
{
    use Queueable;

    protected $sender;
    protected $message;
    protected $conversationId;

    public function __construct($sender, $message, $conversationId)
    {
        $this->sender = $sender;
        $this->message = $message;
        $this->conversationId = $conversationId;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'chat_message',
            'title' => $this->sender->name . ' sent you a message',
            'body' => $this->message,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'sender_avatar' => $this->sender->avatar ?? null,
            'conversation_id' => $this->conversationId,
            'action_url' => '/chat/' . $this->conversationId,
            'created_at' => now()->toISOString(),
        ];
    }
} 