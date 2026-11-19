<?php

namespace App\Http\Requests;

use App\Models\Rol;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/** Validación de la cuenta de personal (HU-04, RF-04.06). El rol Turista no se asigna aquí. */
class PersonalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // el permiso ya lo verifica el middleware de la ruta
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'rol_id' => ['required', Rule::exists('roles', 'id')->whereNot('slug', 'turista')],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ingresa los nombres.',
            'apellidos.required' => 'Ingresa los apellidos.',
            'email.required' => 'Ingresa el correo electrónico.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'Ya existe una cuenta con este correo.',
            'rol_id.required' => 'Selecciona el rol.',
            'rol_id.exists' => 'El rol seleccionado no es válido para personal.',
            'password.required' => 'Ingresa una contraseña temporal.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe incluir al menos una letra.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
        ];
    }

    /** Roles que se pueden asignar a personal (todos menos turista). */
    public static function rolesPersonal()
    {
        return Rol::where('slug', '!=', 'turista')->orderBy('nombre')->get();
    }
}
