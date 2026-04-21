<?php

use App\Http\Controllers\Api\AiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FinanceSummaryController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('ai')->group(function (): void {
        Route::post('/parse-text', [AiController::class, 'parseText']);
        Route::post('/parse-voice', [AiController::class, 'parseVoice']);
        Route::post('/parse-receipt', [AiController::class, 'parseReceipt']);
    });

    Route::apiResource('transactions', TransactionController::class);
    Route::post('/transactions/{transaction}/confirm', [TransactionController::class, 'confirm']);

    Route::get('/finance/summary/weekly', [FinanceSummaryController::class, 'weekly']);
    Route::get('/finance/summary/monthly', [FinanceSummaryController::class, 'monthly']);

    Route::apiResource('tasks', TaskController::class);
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete']);
    Route::post('/tasks/{task}/cancel', [TaskController::class, 'cancel']);

    Route::apiResource('categories', CategoryController::class)->except('show');
});
