<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/** Carga todos los datos de prueba. Uso: php artisan migrate:fresh --seed */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolSeeder::class,
            UsuarioSeeder::class,
            RutaSeeder::class,
            AgenciaSeeder::class,
            TurismoSeeder::class,
        ]);
    }
}
