<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Маршруты API
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::put('/{id}/update', [TaskController::class, 'update'])->name('tasks.update');
        Route::patch('/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    });
});

// Авторизация
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

