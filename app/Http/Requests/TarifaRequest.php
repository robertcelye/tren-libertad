<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Validación de una tarifa (HU-08, RF-02.02). */
class TarifaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_pasajero' => ['required', Rule::in(['nacional', 'extranjero'])],
            'valor' => ['required', 'numeric', 'gt:0', 'max:9999'],
            'cargo_servicio' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_pasajero.required' => 'Selecciona el tipo de pasajero.',
            'valor.required' => 'Ingresa el valor de la tarifa.',
            'valor.gt' => 'El valor debe ser mayor que cero.',
            'fecha_inicio.required' => 'Indica desde cuándo rige la tarifa.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior al inicio.',
        ];
    }
}
