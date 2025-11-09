<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CitaController; 
use App\Http\Controllers\HistoriaMedicaController; // NECESARIO PARA LAS NUEVAS RUTAS
use Illuminate\Support\Facades\Route;

// ==========================================================
// GRUPO PRINCIPAL V1: Prefijo /api/v1
// ==========================================================
Route::prefix('v1')->group(function () {
    
    // ------------------------------------
    // 1. RUTAS PÚBLICAS (NO requieren Token)
    // ------------------------------------
    
    // REGISTRO y LOGIN
    Route::post('users', [UserController::class, 'store']); 
    Route::post('login', [AuthController::class, 'login']); 

    // DOCTORES PÚBLICOS
    Route::get('doctores', [CitaController::class, 'indexDoctores']);
    
    // ----------------------------------------------------
    // 2. RUTAS PROTEGIDAS (REQUIEREN Token 'auth:sanctum')
    // ----------------------------------------------------
    
    Route::middleware('auth:sanctum')->group(function () {
        
        // CRUD de Usuarios y LOGOUT
        Route::apiResource('users', UserController::class)->except(['store']);
        Route::post('logout', [AuthController::class, 'logout']);

        // --- RUTAS DE CITAS ---
        Route::post('citas', [CitaController::class, 'store']);
        Route::get('citas', [CitaController::class, 'indexCitasPaciente']); 
        
        // --- RUTAS DE HISTORIAL MÉDICO Y TRATAMIENTOS ---
        Route::prefix('historial')->group(function () {
            // LECTURA y CREACIÓN
            Route::get('{paciente_id}', [HistoriaMedicaController::class, 'index']); // GET /api/v1/historial/{paciente_id}
            Route::post('/', [HistoriaMedicaController::class, 'store']); // POST /api/v1/historial
            
            // ACTUALIZACIÓN y ELIMINACIÓN de HISTORIA
            Route::put('{historia_id}', [HistoriaMedicaController::class, 'update']); // PUT /api/v1/historial/{historia_id}

            // CREACIÓN, ACTUALIZACIÓN y ELIMINACIÓN de TRATAMIENTOS
            Route::post('{historia_id}/tratamiento', [HistoriaMedicaController::class, 'addTratamiento']); // POST /api/v1/historial/{historia_id}/tratamiento
            Route::put('tratamiento/{tratamiento_id}', [HistoriaMedicaController::class, 'updateTratamiento']); // PUT /api/v1/historial/tratamiento/{tratamiento_id}
            Route::delete('tratamiento/{tratamiento_id}', [HistoriaMedicaController::class, 'destroyTratamiento']); // DELETE /api/v1/historial/tratamiento/{tratamiento_id}
        });
    });
});