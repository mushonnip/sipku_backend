<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [\App\Http\Controllers\API\UserController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\API\UserController::class, 'logout']);
Route::post('register', [\App\Http\Controllers\API\UserController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::resource('transactions', \App\Http\Controllers\API\TransactionController::class);
    Route::resource('categories', \App\Http\Controllers\API\CategoryController::class);
});
