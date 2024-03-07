<?php

use App\Http\Controllers\{
    CatalogController,
    ClientController,
    CurrencyController,
    WarehouseController,
    AuthController,
    RoleController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::get('catalogs/{catalog}', [CatalogController::class, 'index']);
Route::get('catalogs/{catalog}/{id}', [CatalogController::class, 'show']);


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('currencies', CurrencyController::class);
    Route::apiResource('warehouses', WarehouseController::class);
    Route::apiResource('roles', RoleController::class);
});
