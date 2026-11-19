<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistroRequest;
use App\Models\RegistroAuditoria;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Ctrl_Usuarios · Registro del turista (HU-03, RF-04.05, UC-01.5).
 * El registro público siempre crea cuentas con el rol Turista / Visitante.
 */
class RegistroController extends Controller
{
    /** Muestra el formulario de registro. */
    public function create(): View
    {
        return view('auth.registro');
    }

    /** Guarda la cuenta nueva e inicia sesión. */
    public function store(RegistroRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        $usuario = User::create([
            'name' => $datos['name'],
            'apellidos' => $datos['apellidos'],
            'email' => $datos['email'],
            'telefono' => $datos['telefono'] ?? null,
            'password' => $datos['password'],                       // el modelo la guarda como hash
            'activo' => true,
            'rol_id' => Rol::where('slug', 'turista')->value('id'), // rol por defecto
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        RegistroAuditoria::registrarOperacion('registrar_turista', $usuario);

        return redirect()->route('panel')->with('exito', 'Tu cuenta fue creada. ¡Bienvenido al Tren de la Libertad!');
    }
}
