<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::resource('events',EventController::class);
    Route::get('events/{event}/reservations',[EventController::class,'getEventReservations']);
    Route::get(' ',[EventController::class,'getEventImages']);
    Route::resource('users',UserController::class);
    Route::resource('locations',LocationController::class);
    Route::get('locations/{location}/images',[LocationController::class,'getEventImages']);
    Route::resource('event_types',EventTypeController::class);
    Route::resource('reservation',ReservationController::class);
    Route::post('logout', [AuthController::class, 'logout']);
}) ;