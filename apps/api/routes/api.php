<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\AttachmentController;
use App\Http\Controllers\Api\V1\SyncController;
use App\Http\Controllers\Api\V1\IncidentController;
use App\Http\Controllers\Api\V1\HazardController;

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:5,1')->post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/attachments', [AttachmentController::class, 'store']);
        Route::prefix('sync')->group(function () {
            Route::post('/incidents', [SyncController::class, 'incidents']);
            Route::post('/hazards', [SyncController::class, 'hazards']);
        });

        Route::apiResource('incidents', IncidentController::class)->only(['index', 'show', 'update']);
        Route::apiResource('hazards', HazardController::class)->only(['index', 'show', 'update']);
    });
});
