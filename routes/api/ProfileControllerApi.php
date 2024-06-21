<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

class ProfileControllerApi
{
    public static function set()
    {
        Route::group(['middleware' => ['user', 'role:usuario|administracion|soporte']], function () {
            Route::get('/check-url-invite', [ProfileController::class, 'checkUrlInvite']);
            Route::put('update-profile', [ProfileController::class, 'updateProfile']);

            Route::prefix('profile')->group(function () {
                Route::post('/photo', [ProfileController::class, 'changePhoto']);
                Route::put('/update', [ProfileController::class, 'updateMyProfile']);
                Route::post('/delete-wallet', [ProfileController::class, 'deleteWallet']);
            });
        });

        Route::group(['middleware' => ['user', 'role:administracion|soporte']], function () {
            Route::post('create-profile', [ProfileController::class, 'createProfile']);
            Route::delete('delete-profile/{id}', [ProfileController::class, 'deleteProfile']);

            Route::post('profile/{user}/delete-wallet', [ProfileController::class, 'deleteWalletUser'])
                ->middleware('requestAction');

            Route::prefix('profile')->group(function () {
                Route::put('/{profile}/update', [ProfileController::class, 'update']);
            });
        });
    }
}
