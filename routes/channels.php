<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return true;
    \Illuminate\Support\Facades\Log::info("chat connected", [
        'user_id' => $user->id,
        'conversation_id' => $conversationId,
        'user_authenticated' => auth()->check(),
        'user_id_from_auth' => auth()->id()
    ]);

    if ($user->conversations()->where('wire_conversations.id', $conversationId)->exists()){
        return true;
    } else {
        return false;
    }
});
