<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

class UserApi
{
    public static function set()
    {
        Route::put('/users/update', [UserController::class, 'updateMine']);
        Route::post('/users/withdraw/balance', [UserController::class, 'withdrawBalance']);

        Route::get('/users/my-user', [UserController::class, 'myUser']);
        Route::group(['middleware' => ['role:administracion|soporte']], function () {
            Route::post('/users/withdraw/balance/{user}', [UserController::class, 'withdrawBalanceUser']);

            Route::get('/users/trash', [UserController::class, 'trash']);
            Route::get('/users/wallets', [UserController::class, 'wallets']);
            Route::post('/users/walletsUpdate', [UserController::class, 'walletsUpdate']);
            Route::post('/users/{user_id}/unDelete', [UserController::class, 'unDelete']);

            Route::get('/users/rangue-number/{number}', [UserController::class, 'getByRangueNumber']);
            Route::get('/users/rangue-advance', [UserController::class, 'rangueAdvance']);
            Route::put('/users/{user}/profile', [UserController::class, 'updateProfile']);
            Route::post('/users/{user}/ban', [UserController::class, 'ban']);
            Route::post('/users/{user}/unban', [UserController::class, 'unban']);
            Route::post('/users/{user}/unlock', [UserController::class, 'unlock']);
            Route::post('/users/{user}/sponsor', [UserController::class, 'sponsor']);
        });

        Route::resource('/users', UserController::class);
    }
}
