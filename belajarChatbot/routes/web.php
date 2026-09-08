<?php

use Illuminate\Support\Facades\Route;
use App\Models\Chat;
use App\Http\Controllers\ChatbotController;

Route::get('/', function () {
    return view('chat', [
        'session' => \App\Models\ChatSession::latest()->get()
    ]);
});

Route::post('/chat/send', [ChatbotController::class, 'sendMessage']);
Route::get('/chat/session/{sessionId}', [ChatbotController::class, 'getSessionChats']);