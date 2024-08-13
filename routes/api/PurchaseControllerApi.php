<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PaypalController;
use Illuminate\Support\Facades\Route;

class PurchaseControllerApi
{
    public static function set()
    {
        Route::group(['middleware' => ['role:administracion|soporte|usuario']], function () {
            Route::get('purchase', [PurchaseController::class, 'index']);
            Route::post('purchase/save', [PurchaseController::class, 'store']);
            Route::get('purchase/{purchase}', [PurchaseController::class, 'show']);
            Route::put('purchase/{purchase}', [PurchaseController::class, 'update']);
            Route::delete('purchase/{purchase}', [PurchaseController::class, 'destroy']);
            Route::post('purchase/{purchase}/status', [PurchaseController::class, 'statusBuyer']);
            Route::get('purchase/my-purchase', [PurchaseController::class, 'index']);
            Route::post('/purchase/{purchase}/comment', [PurchaseController::class, 'comment']);
        });
    }
}

