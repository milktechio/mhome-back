<?php

use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

class TransactionControllerApi
{
    public static function set()
    {
        Route::group(['middleware' => ['role:administracion|soporte|usuario']], function () {
            Route::get('transaction', [TransactionsController::class, 'index']);
            Route::post('transaction/save', [TransactionsController::class, 'store']);
            Route::get('transaction/{transactions}', [TransactionsController::class, 'show']);
            Route::put('transaction/{transactions}', [TransactionsController::class, 'update']);
            Route::delete('transaction/{transactions}', [TransactionsController::class, 'destroy']);
            Route::post('transaction/{transactions}/status', [TransactionsController::class, 'statusBuyer']);
            Route::get('transaction/my-transaction', [TransactionsController::class, 'index']);
            Route::post('/transaction/{transactions}/comment', [TransactionsController::class, 'comment']);
        });
    }
}

