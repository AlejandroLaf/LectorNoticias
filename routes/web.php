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

Route::middleware(['auth','web'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/periodicos', [PeriodicoWebController::class, 'verNombresPeriodicos', 'index'])->name('periodicos.index');
    Route::get('/periodicos/nuevo', [PeriodicoWebController::class, 'mostrarFormularioAgregar']);
    Route::get('/periodicos/{id}', [PeriodicoWebController::class, 'mostrarDatosPorPeriodico']);
    Route::get('/periodicos/editar/{id}', [PeriodicoWebController::class, 'editarPeriodico']);
    Route::get('/ver-titulares/{id}', [PeriodicoWebController::class, 'verTitularesPeriodico'])->name('titulares.periodico');
    Route::get('/ver-titulares', [PeriodicoWebController::class, 'verTitulares'])->name('titulares.titulares');
});

require __DIR__ . '/auth.php';
