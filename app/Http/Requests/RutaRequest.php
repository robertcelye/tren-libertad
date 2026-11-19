<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Validación de una ruta (HU-06, RF-05.01). */
class RutaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // el permiso lo verifica el middleware de la ruta
    }

    public function rules(): array
    {
        return [
            'codigo' => ['required', 'string', 'max:20', Rule::unique('rutas', 'codigo')->ignore($this->route('ruta'))],
            'nombre' => ['required', 'string', 'max:100'],
            'estacion_origen' => ['required', 'string', 'max:100'],
            'estacion_destino' => ['required', 'string', 'max:100', 'different:estacion_origen'],
            'distancia_km' => ['nullable', 'numeric', 'min:0'],
            'duracion_min' => ['nullable', 'integer', 'min:1'],
            'estado' => ['required', Rule::in(['activa', 'suspendida'])],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'Ingresa el código de la ruta.',
            'codigo.unique' => 'Ya existe una ruta con este código.',
            'nombre.required' => 'Ingresa el nombre de la ruta.',
            'estacion_origen.required' => 'Ingresa la estación de origen.',
            'estacion_destino.required' => 'Ingresa la estación de destino.',
            'estacion_destino.different' => 'El destino debe ser distinto del origen.',
            'distancia_km.numeric' => 'La distancia debe ser un número.',
            'duracion_min.integer' => 'La duración debe ser un número entero de minutos.',
        ];
    }
}
