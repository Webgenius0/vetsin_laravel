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
        false,
        1
    );

    return response()->json([
        'message' => 'Push notification sent successfully',
        'user' => $user->name,
        'device_token' => $user->device_token ? 'Present' : 'Missing'
    ]);
});

// Test route for database notifications
Route::get('/test-database-notification/{email}', function () {
    $email = request()->route('email');
    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Test different types of notifications
    App\Services\NotificationService::sendChatNotification(
        $user, 
        $user, 
        'This is a test database notification message', 
        false,
        1
    );

    App\Services\NotificationService::sendFavoriteNotification($user, $user);

    return response()->json([
        'message' => 'Database notifications sent successfully',
        'user' => $user->name,
        'notifications_enabled' => $user->notifications_enabled,
        'notifications_count' => $user->notifications()->count()
    ]);
});

// Test route for notification toggle
Route::get('/test-notification-toggle/{email}', function () {
    $email = request()->route('email');
    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Toggle notifications
    $oldStatus = $user->notifications_enabled;
    $user->notifications_enabled = !$oldStatus;
    $user->save();

    return response()->json([
        'message' => 'Notification toggle test completed',
        'user' => $user->name,
        'previous_status' => $oldStatus,
        'new_status' => $user->notifications_enabled,
        'status_text' => $user->notifications_enabled ? 'enabled' : 'disabled'
    ]);
});
