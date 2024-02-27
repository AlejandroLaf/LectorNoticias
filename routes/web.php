<?php

use App\Http\Controllers\PeriodicoController;
use App\Http\Controllers\PeriodicoWebController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/ver-periodicos', [PeriodicoController::class, 'verNombresPeriodicos'])->name('listaPeriodicos');
    Route::get('/agregar-periodico', [PeriodicoWebController::class, 'mostrarFormularioAgregar'])->middleware('auth')->name('mostrar-formulario-agregar');
    Route::post('/agregar-periodico', [PeriodicoWebController::class, 'agregarPeriodico'])->middleware('auth')->name('agregar-periodico');
    Route::get('/ver-titulares', [PeriodicoController::class, 'mostrarTitulares'])->name('listaTitulares');
    Route::get('/ver-titulares/{id}', [PeriodicoController::class, 'mostrarTitularesPorPeriodico']);
    Route::get('/ver-periodicos/{id}', [PeriodicoController::class, 'mostrarDatosPorPeriodico']);
    Route::delete('/ver-periodicos/{id}/' , [PeriodicoController::class, 'borrarPeriodico']);
    Route::put('/ver-periodicos/{id}/' , [PeriodicoController::class, 'modificarPeriodico']);
});

require __DIR__ . '/auth.php';
