<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    // Assuming the User model uses the Chatable trait and has conversations relationship
    return $user->conversations()->where('id', $conversationId)->exists();
});
