<?php

use App\Http\Controllers\{EventController};
use Illuminate\Support\Facades\Route;

class EventControllerApi
{
    public static function set()
    {
        Route::group(['middleware' => ['role:administracion|soporte|usuario']], function () {
            Route::get('/events', [EventController::class, 'index']);
            Route::post('/events/save', [EventController::class, 'store']);
            Route::delete('/events/{event}', [EventController::class, 'destroy']);
            Route::get('/events/{event}/like', [EventController::class, 'like']);
            Route::get('/events/{event}', [EventController::class, 'show']);
            Route::post('/events/{event}/comment', [EventController::class, 'comment']);
            Route::delete('/events/comment/{comment}', [EventController::class, 'deleteComment']);
        });
    }
}
