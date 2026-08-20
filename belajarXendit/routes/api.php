<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhookController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function(){
    Route::get('/profile', [AuthController::class, 'profile']);
    
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events{$id}', [EventController::class, 'show']);
    Route::post('/events', [EventController::class, 'store']);
    
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);

});

Route::post('/webhook/xendit', [WebhookController::class, 'handle']);