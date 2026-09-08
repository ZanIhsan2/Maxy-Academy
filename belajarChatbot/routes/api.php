<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

Route::post('/chat', [ChatbotController::class, 'ask']);
Route::get('/history', [ChatbotController::class, 'history']);