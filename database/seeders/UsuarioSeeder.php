<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Un usuario de prueba por cada rol, para probar el sistema en local.
 * Correo: <rol>@tren.test · Contraseña de prueba: password
 */
class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Rol::all() as $rol) {
            User::updateOrCreate(
                ['email' => $rol->slug.'@tren.test'],
                [
                    'name' => $rol->nombre,
                    'apellidos' => 'Prueba',
                    'username' => $rol->slug,
                    'password' => 'password',   // el modelo la guarda como hash
                    'activo' => true,
                    'rol_id' => $rol->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
