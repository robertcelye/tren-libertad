<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsuariosController;
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
});
