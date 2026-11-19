<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistroAuditoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Ctrl_Usuarios · Inicio y cierre de sesión (HU-03, RF-04.01, UC-01.1).
 */
class LoginController extends Controller
{
    /** Muestra el formulario de inicio de sesión. */
    public function create(): View
    {
        return view('auth.login');
    }

    /** Valida las credenciales y abre la sesión. */
    public function store(Request $request): RedirectResponse
    {
        $credenciales = $request->validate(
            ['email' => ['required', 'email'], 'password' => ['required']],
            ['email.required' => 'Ingresa tu correo.', 'email.email' => 'El correo no es válido.', 'password.required' => 'Ingresa tu contraseña.']
        );

        // Solo usuarios activos pueden ingresar.
        if (! Auth::attempt([...$credenciales, 'activo' => true], $request->boolean('recordarme'))) {
            throw ValidationException::withMessages([
                'email' => 'El correo o la contraseña son incorrectos.',
            ]);
        }

        // Nueva sesión para evitar el robo de sesión (session fixation).
        $request->session()->regenerate();

        RegistroAuditoria::registrarOperacion('iniciar_sesion', $request->user());

        return redirect()->intended(route('panel'));
    }

    /** Cierra la sesión. */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio')->with('exito', 'Cerraste sesión correctamente.');
    }
}
