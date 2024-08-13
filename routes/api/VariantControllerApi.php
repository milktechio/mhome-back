<?php

use App\Http\Controllers\VariantController;
use Illuminate\Support\Facades\Route;

class VariantControllerApi
{
    public static function set()
    {
        Route::group(['middleware' => ['role:administracion|soporte|usuario']], function () {
            Route::get('variant', [VariantController::class, 'index']);
            Route::post('variant/save', [VariantController::class, 'store']);
            Route::get('variant/{variant}', [VariantController::class, 'show']);
            Route::put('variant/{variant}', [VariantController::class, 'update']);
            Route::delete('variant/{variant}', [VariantController::class, 'destroy']);
            Route::post('variant/{variant}/image', [VariantController::class, 'updateImage']);
            Route::post('variant/{variant}/status', [VariantController::class, 'updateStatus']);
        });
    }
}
