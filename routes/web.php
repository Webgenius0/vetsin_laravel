<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// Test route for broadcasting
Route::get('/test-broadcast', function () {
    // Create a mock message object that extends stdClass with a load method
    $message = new class extends stdClass {
        public function load($relations = []) {
            return $this;
        }
    };
    $message->id = 1;
    $message->conversation_id = 1;

    broadcast(new App\Events\CustomMessageCreated($message));

    return response()->json(['message' => 'Broadcast sent successfully']);
});

// Test route for push notifications
Route::get('/test-push-notification/{email}', function () {

    $email = request()->route('email');
    $user = \App\Models\User::where('email', $email)->first();

    if (!$user || !$user->device_token) {
        return response()->json(['error' => 'No user with device token found'], 404);
    }

    App\Services\NotificationService::sendChatNotification(
        $user,
        $user,
        'This is a test message from the server',
        false
    );

    return response()->json([
        'message' => 'Push notification sent successfully',
        'user' => $user->name,
        'device_token' => $user->device_token ? 'Present' : 'Missing'
    ]);
});
