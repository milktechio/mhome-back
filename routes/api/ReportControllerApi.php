<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


class ReportControllerApi
{
    public static function set()
    {
        Route::group(['middleware' => ['role:administracion|soporte|usuario']], function() {
            Route::get('report', [ReportController::class, 'index']);
            Route::post('report/save', [ReportController::class, 'store']);
            Route::get('report/{report}', [ReportController::class, 'show']);
            Route::put('report/{report}', [ReportController::class, 'update']);
            Route::post('report/image/{report}', [ReportController::class, 'updateImage']);
            Route::delete('report/{report}', [ReportController::class, 'destroy']);
        });

        Route::group(['middleware' => ['role:administracion|soporte']], function() {
            Route::put('report/status/{report}', [ReportController::class, 'updateStatus']);
        });
    }
}
