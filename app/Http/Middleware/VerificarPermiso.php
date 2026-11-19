<?php

namespace App\Http\Middleware;

use App\Models\RegistroAuditoria;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Componente Seguridad del diagrama MVC (HU-05, RF-04.03, RNF-06).
 * Se ejecuta antes del controlador: si el rol del usuario no tiene el permiso, bloquea el acceso
 * y registra el intento en la auditoría.
 *
 * Uso en rutas: ->middleware('permiso:tarifas.gestionar')
 */
class VerificarPermiso
{
    public function handle(Request $request, Closure $next, string $permiso): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! $usuario->verificarPermiso($permiso)) {
            if ($usuario) {
                RegistroAuditoria::registrarOperacion('acceso_denegado', $usuario, null, ['permiso' => $permiso, 'ruta' => $request->path()], 'fallido');
            }

            abort(403, 'No tienes permiso para realizar esta operación.');
        }

        return $next($request);
    }
}
