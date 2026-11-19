<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Validación para programar viajes por rango de fechas y días de la semana (HU-06, RF-05.01). */
class ProgramarViajesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_inicio' => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio', 'before_or_equal:'.now()->addDays(180)->toDateString()],
            'dias' => ['required', 'array', 'min:1'],
            'dias.*' => ['integer', 'between:0,6'],         // 0 = domingo … 6 = sábado
            'hora_salida' => ['required', 'date_format:H:i'],
            'hora_llegada' => ['nullable', 'date_format:H:i', 'after:hora_salida'],
            'num_vagones' => ['required', 'integer', 'min:1', 'max:10'],
            'asientos_por_vagon' => ['required', 'integer', 'min:1', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_inicio.required' => 'Indica la fecha de inicio.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
            'fecha_fin.required' => 'Indica la fecha de fin.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior al inicio.',
            'fecha_fin.before_or_equal' => 'Solo se pueden programar viajes hasta 180 días adelante.',
            'dias.required' => 'Selecciona al menos un día de la semana.',
            'hora_salida.required' => 'Indica la hora de salida.',
            'hora_salida.date_format' => 'La hora debe tener el formato HH:MM.',
            'hora_llegada.after' => 'La llegada debe ser posterior a la salida.',
            'num_vagones.required' => 'Indica el número de vagones.',
            'asientos_por_vagon.required' => 'Indica los asientos por vagón.',
        ];
    }
}
