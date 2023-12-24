<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', [\App\Http\Controllers\Api\UsersController::class, 'index']);
Route::get('getjson', [\App\Http\Controllers\Api\UsersController::class, 'getjson']);
Route::get('user/{id}', [\App\Http\Controllers\Api\UsersController::class, 'getUserById']);
Route::post('users', [\App\Http\Controllers\Api\UsersController::class, 'store']);
Route::get('users/{user}', [\App\Http\Controllers\Api\UsersController::class, 'show']);
Route::put('users/{user}', [\App\Http\Controllers\Api\UsersController::class, 'update']);
Route::delete('users/{user}', [\App\Http\Controllers\Api\UsersController::class, 'destroy']);

Route::group(['middleware' => ['api']], function () {
    Route::post('user-register', [\App\Http\Controllers\Api\UsersController::class, 'userRegister']);
    Route::post('user-auth', [\App\Http\Controllers\Api\UsersController::class, 'userAuth']);
});






