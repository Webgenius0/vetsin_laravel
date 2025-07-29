<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Namu\WireChat\Facades\WireChat;

class CustomMessageCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithQueue,InteractsWithSockets, Queueable ,SerializesModels;

    public $message;


    /**
     * Create a new event instance.
     *
     * @param  mixed  $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::info("broadcastOn conversation_id", [
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'channel_name' => 'chat.' . $this->message->conversation_id
        ]);
        // You can customize the channel as needed
        return new PrivateChannel('chat.' . $this->message->conversation_id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'custom.message.created';
    }
}
