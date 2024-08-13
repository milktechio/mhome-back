<?php

use Illuminate\Support\Facades\Route;

include_once __DIR__ . '/api/index.php';

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['json'])->group(function () {
AuthControllerApi::set();
ProfileControllerApi::set();
// EmailControllerApi::set();
AuthControllerApi::forWeb();
// DocumentControllerApi::public();

Route::middleware(['user'])->group(function () {
    // ModuleControllerApi::set();
    VoteControllerApi::set();
    EventControllerApi::set();
    UserApi::set();
    ProductControllerApi::set();
    VariantControllerApi::set();
    PurchaseControllerApi::set();
    TransactionControllerApi::set();
    // UserDeleteApi::set();
    // RolesApi::set();
    // DashboardApi::set();
    // DocumentControllerApi::set();
});
});

Route::middleware(['bearerJwt'])->group(function () {
    AuthControllerApi::check();
});
