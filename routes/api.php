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


Route::middleware('auth:api')->get('/periodicos', [PeriodicoController::class, 'verNombresPeriodicos'])->name('api.verPeriodicos');
Route::post('/periodicos/agregar', [PeriodicoController::class,'agregarPeriodico'])->name('api.agregarPeriodico');

Route::middleware(['auth:api'])->group(function () {

    // Otras rutas de la API aquí...
});
