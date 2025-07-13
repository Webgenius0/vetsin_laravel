<?php

use App\Http\Controllers\Api\Chat\ChatController;
use Illuminate\Support\Facades\Route;


// get all chats
Route::get('/chats', [ChatController::class, 'chats']);
//get single chat details
Route::get('/chat/{user_id}', [ChatController::class, 'chat']);
//send message
Route::post('/chat/send', [ChatController::class, 'sendMessage']);
