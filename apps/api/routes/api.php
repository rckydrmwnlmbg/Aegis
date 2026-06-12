<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\PtwController;
use App\Http\Controllers\Api\V1\IncidentController;
use App\Http\Controllers\Api\V1\CapaController;
use App\Http\Controllers\Api\V1\SyncController;
use App\Http\Controllers\Api\V1\CopilotController;
use App\Http\Controllers\Api\V1\JsaController;

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

Route::post('/v1/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/auth/logout', [AuthController::class, 'logout']);
    Route::get('/v1/auth/me', [AuthController::class, 'me']);

    // PTW Routes
    Route::get('/v1/ptw', [PtwController::class, 'index']);
    Route::get('/v1/ptw/{id}', [PtwController::class, 'show']);
    Route::post('/v1/ptw', [PtwController::class, 'store']);
    Route::put('/v1/ptw/{id}/status', [PtwController::class, 'updateStatus']);

    // JSA Routes
    Route::post('/v1/jsa', [JsaController::class, 'store']);

    // Incident Routes
    Route::get('/v1/incidents', [IncidentController::class, 'index']);
    Route::get('/v1/incidents/{id}', [IncidentController::class, 'show']);
    Route::post('/v1/incidents', [IncidentController::class, 'store']); // Triage / manual entry
    Route::put('/v1/incidents/{id}', [IncidentController::class, 'update']);
    Route::put('/v1/incidents/{id}/severity', [IncidentController::class, 'updateSeverity']);

    // CAPA Routes
    Route::get('/v1/capa', [CapaController::class, 'index']);
    Route::get('/v1/capa/{id}', [CapaController::class, 'show']);
    Route::post('/v1/capa', [CapaController::class, 'store']);
    Route::put('/v1/capa/{id}/status', [CapaController::class, 'updateStatus']);

    // Copilot
    Route::post('/v1/copilot/chat', [CopilotController::class, 'chat']);

    // Sync Endpoint for offline-first (referenced in phase 2 plan)
    Route::post('/v1/sync/incidents', [SyncController::class, 'incidents']);

    // Inspections
    Route::get('/v1/inspection-templates', [\App\Http\Controllers\InspectionTemplateController::class, 'index']);
    Route::post('/v1/inspection-templates', [\App\Http\Controllers\InspectionTemplateController::class, 'store']);
    Route::get('/v1/inspections', [\App\Http\Controllers\InspectionController::class, 'index']);
    Route::post('/v1/inspections', [\App\Http\Controllers\InspectionController::class, 'store']);
    Route::post('/v1/inspections/{id}/complete', [\App\Http\Controllers\InspectionController::class, 'complete']);
});
