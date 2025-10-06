<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::apiResource('events', EventController::class);

// Route::apiResource('events.attendees', AttendeeController::class)
//     ->scoped(['attendee' => 'event']);

Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped()->except(['update']);

Route::get('/test', fn() => 'ok');

// Route::get('/debug-routes', function () {
//     return response()->json([
//         // 'user' => auth()->user(),
//         'routes' => Route::getRoutes()->getRoutesByName()
//     ]);
// });
