<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonalRequest;
use App\Models\RegistroAuditoria;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Ctrl_Usuarios · Cuentas de personal (HU-04, RF-04.06, UC-01.6).
 * Solo el administrador del sistema (permiso usuarios.crear_personal) llega aquí.
 */
class UsuariosController extends Controller
{
    /** Lista las cuentas de personal (todas menos turistas). */
    public function index(): View
    {
        $usuarios = User::with('rol')
            ->whereHas('rol', fn ($q) => $q->where('slug', '!=', 'turista'))
            ->orderBy('name')
            ->paginate(15);

        return view('usuarios.index', compact('usuarios'));
    }

    /** Formulario para crear una cuenta de personal. */
    public function create(): View
    {
        return view('usuarios.crear', ['roles' => PersonalRequest::rolesPersonal()]);
    }

    /** Crea la cuenta con el rol elegido. */
    public function store(PersonalRequest $request): RedirectResponse
    {
        $usuario = User::create([...$request->validated(), 'activo' => true]);

        RegistroAuditoria::registrarOperacion('crear_cuenta_personal', $usuario, null, [
            'email' => $usuario->email,
            'rol' => $usuario->rol->slug,
        ]);

        return redirect()->route('usuarios.index')
            ->with('exito', "Se creó la cuenta de {$usuario->nombre_completo} con el rol {$usuario->rol->nombre}.");
    }

    /** Activa o desactiva una cuenta (un usuario inactivo no puede iniciar sesión). */
    public function cambiarEstado(User $usuario): RedirectResponse
    {
        if ($usuario->is(auth()->user())) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $antes = ['activo' => $usuario->activo];
        $usuario->update(['activo' => ! $usuario->activo]);

        RegistroAuditoria::registrarOperacion('cambiar_estado_usuario', $usuario, $antes, ['activo' => $usuario->activo]);

        return back()->with('exito', $usuario->activo ? 'Cuenta activada.' : 'Cuenta desactivada.');
    }
}
