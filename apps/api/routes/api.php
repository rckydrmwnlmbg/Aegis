<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\AttachmentController;
use App\Http\Controllers\Api\V1\SyncController;
use App\Http\Controllers\Api\V1\IncidentController;
use App\Http\Controllers\Api\V1\HazardController;
use App\Http\Controllers\Api\V1\CapaController;
use App\Http\Controllers\Api\V1\PermitToWorkController;
use App\Http\Controllers\Api\V1\JsaController;
use App\Http\Controllers\Api\V1\ContractorController;

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::middleware('throttle:5,1')->post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/attachments', [AttachmentController::class, 'store']);

        // Sync routes
        Route::prefix('sync')->group(function () {
            Route::post('/incidents', [SyncController::class, 'incidents']);
            Route::post('/hazards', [SyncController::class, 'hazards']);
        });

        // Incident routes
        Route::apiResource('incidents', IncidentController::class)->only(['index', 'show', 'update']);

        // Hazard routes
        Route::apiResource('hazards', HazardController::class)->only(['index', 'show', 'update']);

        // CAPA routes
        Route::prefix('capa')->group(function () {
            Route::post('/', [CapaController::class, 'store']);
            Route::get('/my-tasks', [CapaController::class, 'myTasks']);
            Route::post('/{id}/evidence', [CapaController::class, 'uploadEvidence']);
            Route::patch('/{id}/verify', [CapaController::class, 'verify']);
        });

        // PTW routes
        Route::post('/ptw', [PermitToWorkController::class, 'store']);
        Route::patch('/ptw/{permit}/status', [PermitToWorkController::class, 'updateStatus']);
        Route::post('/ptw/{permit}/workers', [PermitToWorkController::class, 'addWorker']);

        // JSA routes
        Route::post('/jsa', [JsaController::class, 'store']);

        // Contractor routes
        Route::apiResource('contractors', ContractorController::class)->only(['index']);
    });
});
