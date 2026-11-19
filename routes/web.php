<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RutasController;
use App\Http\Controllers\TarifasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ViajesController;
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

    // HU-04: cuentas de personal (administrador del sistema).
    Route::middleware('permiso:usuarios.crear_personal')->group(function () {
        Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UsuariosController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuarios.store');
        Route::patch('/usuarios/{usuario}/estado', [UsuariosController::class, 'cambiarEstado'])->name('usuarios.estado');
    });

    // HU-05: permisos por rol (administrador de seguridad).
    Route::middleware('permiso:roles.permisos')->group(function () {
        Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
        Route::get('/roles/{rol}/editar', [RolesController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{rol}', [RolesController::class, 'update'])->name('roles.update');
    });

    // ---------- Módulo 2: Rutas, viajes y horarios ----------

    // HU-06: rutas (operador ferroviario, administrador del sistema).
    Route::middleware('permiso:rutas.gestionar')->group(function () {
        Route::get('/gestion/rutas', [RutasController::class, 'index'])->name('rutas.index');
        Route::get('/gestion/rutas/crear', [RutasController::class, 'create'])->name('rutas.create');
        Route::post('/gestion/rutas', [RutasController::class, 'store'])->name('rutas.store');
        Route::get('/gestion/rutas/{ruta}/editar', [RutasController::class, 'edit'])->name('rutas.edit');
        Route::put('/gestion/rutas/{ruta}', [RutasController::class, 'update'])->name('rutas.update');
    });

    // HU-06 y HU-07: viajes, frecuencias, vagones y capacidad.
    Route::middleware('permiso:viajes.gestionar')->group(function () {
        Route::get('/gestion/rutas/{ruta}/viajes', [ViajesController::class, 'index'])->name('viajes.index');
        Route::get('/gestion/rutas/{ruta}/viajes/programar', [ViajesController::class, 'create'])->name('viajes.create');
        Route::post('/gestion/rutas/{ruta}/viajes', [ViajesController::class, 'store'])->name('viajes.store');
        Route::get('/gestion/viajes/{viaje}/capacidad', [ViajesController::class, 'editCapacidad'])->name('viajes.capacidad');
        Route::put('/gestion/viajes/{viaje}/capacidad', [ViajesController::class, 'updateCapacidad'])->name('viajes.capacidad.update');
    });

    // HU-08: tarifas (administrador del sistema).
    Route::middleware('permiso:tarifas.gestionar')->group(function () {
        Route::get('/gestion/rutas/{ruta}/tarifas', [TarifasController::class, 'index'])->name('tarifas.index');
        Route::get('/gestion/rutas/{ruta}/tarifas/crear', [TarifasController::class, 'create'])->name('tarifas.create');
        Route::post('/gestion/rutas/{ruta}/tarifas', [TarifasController::class, 'store'])->name('tarifas.store');
        Route::patch('/gestion/tarifas/{tarifa}/desactivar', [TarifasController::class, 'desactivar'])->name('tarifas.desactivar');
    });
});
