<?php

namespace Database\Seeders;

use App\Models\Emprendimiento;
use App\Models\Ruta;
use Illuminate\Database\Seeder;

/** Emprendimientos de prueba vinculados a la ruta (uno pendiente de autorización). */
class TurismoSeeder extends Seeder
{
    public function run(): void
    {
        $ruta = Ruta::where('codigo', 'IBA-SAL')->first();

        $datos = [
            ['Comedor comunitario Salinas (prueba)', 'gastronomia', 'Salinas', 'autorizado'],
            ['Taller de artesanías en caña (prueba)', 'artesania', 'Salinas', 'autorizado'],
            ['Guías del valle del Chota (prueba)', 'guianza', 'Ibarra', 'pendiente'],
        ];

        foreach ($datos as [$nombre, $tipo, $ubicacion, $estado]) {
            $emprendimiento = Emprendimiento::updateOrCreate(['nombre' => $nombre], [
                'tipo' => $tipo,
                'ubicacion' => $ubicacion,
                'descripcion' => 'Registro de prueba para el catálogo turístico.',
                'estado_autorizacion' => $estado,
            ]);

            if ($ruta && $estado === 'autorizado') {
                $emprendimiento->vincularRuta($ruta);
            }
        }
    }
}
