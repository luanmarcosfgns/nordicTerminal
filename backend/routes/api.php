<?php
use App\Http\Controllers\ComandController;
use App\Http\Controllers\ConnectionController;

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\SSHController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('me', [UserController::class, 'me']);
    Route::post('permissions', [UserController::class, 'permissions']);
});

Route::get('/auth/google/url', [GoogleController::class, 'getAuthUrl']);
Route::post('/auth/google/token', [GoogleController::class, 'handleCallback']);
Route::post('users', [UserController::class, 'store']);
Route::get('users/list', [UserController::class, 'find'])->middleware('auth');
Route::resource('users', UserController::class)->except('store')->middleware('auth');


Route::get('/connections/list', [ConnectionController::class, 'find']);
Route::resource('connections', ConnectionController::class)->middleware('auth');

Route::get('/comands/list', [ComandController::class, 'find']);
Route::resource('comands', ComandController::class)->middleware('auth');

Route::put('/ssh/execute/{connection_id}', [SSHController::class, 'execute']);
Route::middleware('throttle:3000,1')->group(function () {
    Route::get('/ssh/status/{execution_id}', [SSHController::class, 'checkStatus']);
});

