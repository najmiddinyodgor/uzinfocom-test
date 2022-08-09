<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => 'auth',
  'as' => 'auth.'
], function () {
  Route::post('login', [AuthController::class, 'login'])->name('login');
  Route::get('me', [AuthController::class, 'me'])->name('me');
  Route::get('logout', [AuthController::class, 'logout'])->name('logout');
  Route::get('refresh', [AuthController::class, 'refresh'])->name('refresh');
});


Route::group([
  'middleware' => 'auth:api'
], function () {
  Route::apiResource('uploads', UploadController::class)
    ->except(['update']);
});
