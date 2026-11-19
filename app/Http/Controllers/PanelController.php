<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/** Panel principal del usuario autenticado: muestra su rol y lo que puede hacer. */
class PanelController extends Controller
{
    public function __invoke(Request $request): View
    {
        $usuario = $request->user()->load('rol');

        return view('panel', [
            'usuario' => $usuario,
            'permisos' => $usuario->rol?->listarPermisos() ?? [],
        ]);
    }
}
