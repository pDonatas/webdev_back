<?php

use App\Http\Controllers\Api\Security\Auth\AuthController;
use App\Http\Controllers\Api\Security\Auth\TokenController;
use App\Http\Controllers\Api\User\UserController;
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


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('/users', UserController::class)->middleware('throttle:1000,60');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/users/search', [UserController::class, 'search'])->name('users.search');
});
