<?php

namespace Database\Seeders;

use App\Models\Agencia;
use App\Models\User;
use Illuminate\Database\Seeder;

/** Agencias de prueba con su límite de boletos por viaje. */
class AgenciaSeeder extends Seeder
{
    public function run(): void
    {
        $usuarioAgencia = User::where('email', 'agencia@tren.test')->first();

        Agencia::updateOrCreate(['ruc' => '1091234567001'], [
            'user_id' => $usuarioAgencia?->id,
            'razon_social' => 'Andes Tours (prueba)',
            'email' => 'agencia@tren.test',
            'limite_boletos' => 10,
        ]);

        Agencia::updateOrCreate(['ruc' => '1797654321001'], [
            'razon_social' => 'Imbabura Viajes (prueba)',
            'limite_boletos' => 8,
        ]);
    }
}
