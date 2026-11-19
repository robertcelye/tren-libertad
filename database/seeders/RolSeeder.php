<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

/** Los 11 roles de la sección 8 del caso y sus permisos iniciales (RF-04.03). */
class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['turista', 'Turista / Visitante', 'Consulta, reserva y compra boletos.',
                ['viajes.ver', 'reservas.crear', 'pagos.realizar', 'boletos.ver_propios', 'turismo.ver']],
            ['operador_boleteria', 'Operador de boletería', 'Vende y emite boletos en ventanilla.',
                ['viajes.ver', 'reservas.crear', 'ventas.ventanilla', 'boletos.emitir']],
            ['agencia', 'Agencia / Operador turístico', 'Reserva cupos dentro de su límite.',
                ['viajes.ver', 'reservas.crear', 'reservas.ver_propias']],
            ['emprendedor', 'Emprendedor / Prestador', 'Publica sus servicios turísticos.',
                ['turismo.ver', 'emprendimientos.gestionar_propios']],
            ['gestor_turistico', 'Gestor turístico', 'Administra atractivos y los vincula con viajes.',
                ['turismo.ver', 'emprendimientos.gestionar', 'emprendimientos.vincular']],
            ['personal_control', 'Personal de control', 'Valida boletos en el abordaje.',
                ['validacion.realizar']],
            ['operador_ferroviario', 'Operador ferroviario', 'Gestiona rutas, viajes, pasajeros e incidencias.',
                ['viajes.ver', 'rutas.gestionar', 'viajes.gestionar', 'operacion.pasajeros', 'incidencias.registrar']],
            ['administrador_sistema', 'Administrador del sistema', 'Configura tarifas, límites y cuentas de personal.',
                ['viajes.ver', 'rutas.gestionar', 'viajes.gestionar', 'tarifas.gestionar', 'agencias.limites',
                    'usuarios.crear_personal', 'emprendimientos.autorizar']],
            ['gestor_institucional', 'Gestor institucional', 'Consulta indicadores y publica promociones.',
                ['reportes.ver', 'promociones.gestionar', 'informacion.consultar']],
            ['soporte_ti', 'Soporte técnico TI', 'Monitorea la plataforma y los respaldos.',
                ['monitoreo.ver', 'respaldos.gestionar']],
            ['administrador_seguridad', 'Administrador de seguridad', 'Gestiona permisos y revisa la auditoría.',
                ['roles.permisos', 'auditoria.ver']],
        ];

        foreach ($roles as [$slug, $nombre, $descripcion, $permisos]) {
            Rol::updateOrCreate(['slug' => $slug], compact('nombre', 'descripcion', 'permisos'));
        }
    }
}
