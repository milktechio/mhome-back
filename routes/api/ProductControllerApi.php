<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

class ProductControllerApi
{
    public static function set()
    {
        Route::group(['middleware' => ['role:administracion|soporte|usuario']], function () {
            Route::get('product', [ProductController::class, 'index']);
            Route::post('product/save', [ProductController::class, 'store']);
            Route::get('product/{product}', [ProductController::class, 'show']);
            Route::put('product/{product}', [ProductController::class, 'update']);
            Route::delete('product/{product}', [ProductController::class, 'destroy']);
            Route::post('product/{product}/image', [ProductController::class, 'updateImage']);

        });
    }
}
