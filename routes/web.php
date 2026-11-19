<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

/*
| Rutas web: unen cada URL con su controlador.
| Son las "interfaces" del diagrama de componentes entre la Vista y el Controlador.
| Cada módulo agregará aquí sus rutas en los siguientes sprints.
*/

Route::view('/', 'inicio')->name('inicio');

// ---------- Módulo 1: Usuarios y acceso (HU-03) ----------

// Solo visitantes sin sesión.
Route::middleware('guest')->group(function () {
    Route::get('/registro', [RegistroController::class, 'create'])->name('register');
    Route::post('/registro', [RegistroController::class, 'store']);

    Route::get('/ingresar', [LoginController::class, 'create'])->name('login');
    Route::post('/ingresar', [LoginController::class, 'store'])->middleware('throttle:5,1'); // máx. 5 intentos por minuto
});

// Solo usuarios con sesión iniciada.
Route::middleware('auth')->group(function () {
    Route::get('/panel', PanelController::class)->name('panel');
    Route::post('/salir', [LoginController::class, 'destroy'])->name('logout');
});
