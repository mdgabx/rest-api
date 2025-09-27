<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::apiResource('events', EventController::class);

Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped(['attendee' => 'event']);

