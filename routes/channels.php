<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {

    \Illuminate\Support\Facades\Log::info("chat connected", [
        'user_id' => $user->id,
        'conversation_id' => $conversationId
    ]);
    if ($user->conversations()->where('id', $conversationId)->exists()){
        return true;
        \Illuminate\Support\Facades\Log::info("connected to chat");
    } else {
        return false;
    }
});
