<?php

use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

class VoteControllerApi
{
    public static function set()
    {
        Route::group(['middleware' => ['role:administracion|soporte']], function () {
            Route::post('vote', [VoteController::class, 'store']);
            Route::put('vote/{vote}', [VoteController::class, 'update']);
            Route::delete('vote/{vote}', [VoteController::class, 'destroy']);
        });
        Route::group(['middleware' => ['role:administracion|soporte|usuario']], function () {
            Route::get('vote', [VoteController::class, 'index']);
            Route::get('vote/{vote}', [VoteController::class, 'show']);
            Route::post('voting', [VoteController::class, 'voting']);
            Route::get('vote/{vote}/result', [VoteController::class, 'result']);
        });
    }
}
