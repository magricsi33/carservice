<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{client_id}/cars', [ClientController::class, 'getClientCars']);
Route::get('/cars/{client_id}/{car_id}/services', [ClientController::class, 'getCarServiceLogs']);
Route::get('/search-client', [ClientController::class, 'searchClient']);
