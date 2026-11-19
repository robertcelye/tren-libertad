<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Validación del formulario de registro del turista (HU-03, RF-04.05).
 * Separar la validación del controlador mantiene el controlador corto (capa Controlador).
 */
class RegistroRequest extends FormRequest
{
    /** Cualquier visitante puede registrarse. */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'acepta_datos' => ['accepted'],   // consentimiento de tratamiento de datos (LOPDP)
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ingresa tus nombres.',
            'apellidos.required' => 'Ingresa tus apellidos.',
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'Ya existe una cuenta con este correo.',
            'password.required' => 'Ingresa una contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe incluir al menos una letra.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'acepta_datos.accepted' => 'Debes aceptar el tratamiento de tus datos personales.',
        ];
    }
}
