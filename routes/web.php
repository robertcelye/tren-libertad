<?php

use Illuminate\Support\Facades\Route;

/*
| Rutas web: unen cada URL con su controlador.
| Son las "interfaces" del diagrama de componentes entre la Vista y el Controlador.
| Cada módulo agregará aquí sus rutas en los siguientes sprints.
*/

Route::view('/', 'inicio')->name('inicio');
