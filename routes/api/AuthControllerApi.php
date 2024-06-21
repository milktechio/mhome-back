<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

class AuthControllerApi
{
    public static function set()
    {
        Route::prefix('auth')->group(function () {
            Route::post('login', [AuthController::class, 'login']);
            Route::post('login/admin', [AuthController::class, 'loginAdmin'])->middleware('jwtBackoffice');
            Route::post('logout', [AuthController::class, 'logout'])->middleware('user')->name('logout');
            Route::post('register', [AuthController::class,'register']);
            Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::post('/migration', [AuthController::class, 'migration']);
            Route::post('/migration/check', [AuthController::class, 'migrationCheck']);
            Route::post('invite-register', [AuthController::class, 'inviteRegister']);
            Route::post('invite/{username}', [UserController::class, 'userExists']);

            Route::post('check-email', [AuthController::class, 'checkEmail']);
            Route::post('check-username', [AuthController::class, 'checkUsername']);
            Route::post('check-password', [AuthController::class, 'checkPassword']);
            Route::get('check-wallet/{wallet}', [AuthController::class, 'checkWallet']);

            Route::post('recover-password-web', [AuthController::class, 'recoverPasswordWeb']);
            Route::post('recover-password', [AuthController::class, 'recoverPassword']);
            Route::post('change-password', [AuthController::class, 'changePassword']);
        });
    }

    public static function check()
    {
        Route::prefix('auth')->group(function () {
            Route::post('check', [AuthController::class, 'check']);
        });
    }

    public static function forWeb()
    {
        Route::prefix('auth')->group(function () {
            Route::post('change-password/{token_uuid}', [AuthController::class, 'verifyTokenPassword']);
        });
    }
}
