<?php

/*
| Catálogo de permisos del sistema (HU-05, RF-04.03).
| Cada clave es una operación; el valor es la descripción que ve el administrador de seguridad.
| Los roles guardan una lista de estas claves (columna roles.permisos).
*/

return [
    'Viajes y oferta' => [
        'viajes.ver' => 'Consultar viajes y disponibilidad',
        'rutas.gestionar' => 'Administrar rutas, horarios y frecuencias',
        'viajes.gestionar' => 'Programar viajes y asignar capacidad',
        'tarifas.gestionar' => 'Administrar tarifas vigentes',
        'agencias.limites' => 'Configurar límites por agencia',
    ],
    'Venta y boletos' => [
        'reservas.crear' => 'Reservar cupos',
        'reservas.ver_propias' => 'Consultar sus propias reservas',
        'pagos.realizar' => 'Pagar en línea',
        'ventas.ventanilla' => 'Vender en ventanilla',
        'boletos.emitir' => 'Emitir boletos',
        'boletos.ver_propios' => 'Consultar sus propios boletos',
    ],
    'Abordaje y operación' => [
        'validacion.realizar' => 'Validar boletos en el abordaje',
        'operacion.pasajeros' => 'Consultar la lista de pasajeros',
        'incidencias.registrar' => 'Registrar novedades e incidencias',
    ],
    'Turismo' => [
        'turismo.ver' => 'Consultar el catálogo turístico',
        'emprendimientos.gestionar_propios' => 'Publicar sus propios servicios',
        'emprendimientos.gestionar' => 'Administrar atractivos y servicios',
        'emprendimientos.vincular' => 'Vincular servicios con viajes',
        'emprendimientos.autorizar' => 'Autorizar emprendimientos',
        'promociones.gestionar' => 'Publicar promociones y paquetes',
    ],
    'Gestión y control' => [
        'reportes.ver' => 'Consultar indicadores y reportes',
        'informacion.consultar' => 'Consultar la información propia',
        'monitoreo.ver' => 'Monitorear la plataforma',
        'respaldos.gestionar' => 'Gestionar respaldos',
    ],
    'Seguridad' => [
        'usuarios.crear_personal' => 'Crear cuentas de personal',
        'roles.permisos' => 'Asignar permisos a los roles',
        'auditoria.ver' => 'Consultar el registro de auditoría',
    ],
];
