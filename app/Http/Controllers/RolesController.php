<?php

namespace App\Http\Controllers;

use App\Models\RegistroAuditoria;
use App\Models\Rol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Ctrl_Usuarios · Permisos por rol (HU-05, RF-04.03, UC-01.3).
 * Solo el administrador de seguridad (permiso roles.permisos) llega aquí.
 */
class RolesController extends Controller
{
    /** Lista los 11 roles con su número de usuarios y permisos. */
    public function index(): View
    {
        $roles = Rol::withCount('usuarios')->orderBy('nombre')->get();

        return view('roles.index', compact('roles'));
    }

    /** Formulario con el catálogo de permisos para un rol. */
    public function edit(Rol $rol): View
    {
        return view('roles.editar', [
            'rol' => $rol,
            'catalogo' => config('permisos'),
        ]);
    }

    /** Guarda los permisos marcados y registra el cambio en auditoría. */
    public function update(Request $request, Rol $rol): RedirectResponse
    {
        $permisosValidos = array_keys(Arr::collapse(config('permisos')));

        $datos = $request->validate([
            'permisos' => ['array'],
            'permisos.*' => [Rule::in($permisosValidos)],
        ]);

        $nuevos = array_values($datos['permisos'] ?? []);

        // Evita que la seguridad quede sin administrador: su rol siempre conserva roles.permisos.
        if ($rol->slug === 'administrador_seguridad' && ! in_array('roles.permisos', $nuevos, true)) {
            return back()->with('error', 'El administrador de seguridad debe conservar el permiso para asignar permisos.');
        }

        $antes = $rol->listarPermisos();
        $rol->update(['permisos' => $nuevos]);

        RegistroAuditoria::registrarOperacion('cambiar_permisos_rol', $rol, ['permisos' => $antes], ['permisos' => $nuevos]);

        return redirect()->route('roles.index')->with('exito', "Se actualizaron los permisos del rol {$rol->nombre}.");
    }
}
