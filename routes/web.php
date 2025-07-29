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
    
    return response()->json(['message' => 'Broadcast sent']);
});
