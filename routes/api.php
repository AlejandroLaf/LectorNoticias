<?php

use App\Http\Controllers\PeriodicoController;
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

Route::middleware(['auth','cors'])->group(function () {
    // Rutas de la API
    Route::get('/periodicos', [PeriodicoController::class, 'verNombresPeriodicos'])->name('api.verPeriodicos');
    Route::post('/periodicos/agregar', [PeriodicoController::class,'agregarPeriodico'])->name('api.agregarPeriodico');
    Route::get('/periodicos/{id}', [PeriodicoController::class, 'mostrarDatosPorPeriodico']);
    Route::delete('/periodicos/{id}', [PeriodicoController::class, 'borrarPeriodico']);
    Route::put('/periodicos/{id}', [PeriodicoController::class, 'modificarPeriodico']);
    Route::get('/ver-titulares', [PeriodicoController::class, 'mostrarTitulares']);
    Route::get('/ver-titulares/{id}', [PeriodicoController::class, 'mostrarTitularesPorPeriodico']);
});

