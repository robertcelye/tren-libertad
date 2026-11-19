<?php

namespace Database\Seeders;

use App\Models\Ruta;
use Illuminate\Database\Seeder;

/** Ruta Ibarra – Salinas con sus tarifas (sección 4.2) y viajes de prueba para los próximos 14 días. */
class RutaSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = Ruta::updateOrCreate(['codigo' => 'IBA-SAL'], [
            'nombre' => 'Ibarra – Salinas',
            'estacion_origen' => 'Ibarra',
            'estacion_destino' => 'Salinas',
            'distancia_km' => 30,
            'duracion_min' => 120,
            'estado' => 'activa',
        ]);

        // Tarifas vigentes: nacionales USD 20, extranjeros USD 40 (sección 4.2 del caso).
        foreach (['nacional' => 20, 'extranjero' => 40] as $tipo => $valor) {
            $ruta->tarifas()->updateOrCreate(
                ['tipo_pasajero' => $tipo, 'fecha_inicio' => now()->startOfYear()->toDateString()],
                ['valor' => $valor, 'cargo_servicio' => 1.50, 'moneda' => 'USD', 'activa' => true]
            );
        }

        // Viajes de prueba: uno por día, 2 vagones de 40 asientos.
        for ($dia = 1; $dia <= 14; $dia++) {
            $ruta->viajes()->updateOrCreate(
                ['fecha' => now()->addDays($dia)->toDateString(), 'hora_salida' => '08:00'],
                ['hora_llegada' => '10:00', 'num_vagones' => 2, 'capacidad' => 80, 'estado' => 'programado']
            );
        }
    }
}
