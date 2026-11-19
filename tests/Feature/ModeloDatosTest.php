<?php

namespace Tests\Feature;

use App\Models\Agencia;
use App\Models\Boleto;
use App\Models\Pasajero;
use App\Models\Reserva;
use App\Models\Ruta;
use App\Models\User;
use App\Models\Validacion;
use App\Models\Viaje;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** HU-02: pruebas del modelo de datos (relaciones, composiciones y reglas básicas). */
class ModeloDatosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_los_datos_de_prueba_se_cargan(): void
    {
        $this->assertSame(11, DB::table('roles')->count());
        $this->assertSame(11, User::count());
        $this->assertSame(14, Viaje::count());
        $this->assertNotNull(Ruta::first()->tarifaVigente('nacional'));
    }

    public function test_cada_usuario_tiene_su_rol_y_permisos(): void
    {
        $admin = User::where('email', 'administrador_sistema@tren.test')->first();
        $turista = User::where('email', 'turista@tren.test')->first();

        $this->assertTrue($admin->tieneRol('administrador_sistema'));
        $this->assertTrue($admin->verificarPermiso('tarifas.gestionar'));
        $this->assertFalse($turista->verificarPermiso('tarifas.gestionar'));
    }

    public function test_la_disponibilidad_descuenta_reservados_y_vendidos(): void
    {
        $viaje = Viaje::first();
        $viaje->update(['cupos_reservados' => 5, 'cupos_vendidos' => 70]);

        $this->assertSame(5, $viaje->consultarDisponibilidad());
    }

    public function test_el_limite_por_agencia_se_respeta(): void
    {
        $agencia = Agencia::where('ruc', '1091234567001')->first(); // límite 10
        $viaje = Viaje::first();

        $this->assertTrue($agencia->validarLimite($viaje, 10));
        $this->assertFalse($agencia->validarLimite($viaje, 11));
    }

    public function test_el_documento_del_pasajero_se_guarda_cifrado(): void
    {
        $pasajero = $this->crearPasajero();

        $guardado = DB::table('pasajeros')->where('id', $pasajero->id)->value('numero_documento');

        $this->assertNotSame('1712345678', $guardado);
        $this->assertSame('1712345678', $pasajero->fresh()->numero_documento);
    }

    public function test_composiciones_eliminan_en_cascada(): void
    {
        $viaje = Viaje::first();
        $reserva = Reserva::create([
            'codigo' => 'R-0001', 'viaje_id' => $viaje->id, 'pasajero_id' => $this->crearPasajero()->id,
            'cantidad' => 1, 'monto_total' => 21.50, 'canal' => 'web',
        ]);
        $boleto = Boleto::create([
            'reserva_id' => $reserva->id, 'pasajero_id' => $reserva->pasajero_id, 'viaje_id' => $viaje->id, 'precio' => 21.50,
        ]);
        Validacion::create(['boleto_id' => $boleto->id, 'fecha_hora' => now(), 'resultado' => 'valido']);

        $this->assertNotEmpty($boleto->codigo);          // UUID asignado automáticamente

        $reserva->delete();                              // Reserva —◆ Boleto —◆ Validacion

        $this->assertSame(0, Boleto::count());
        $this->assertSame(0, Validacion::count());
    }

    private function crearPasajero(): Pasajero
    {
        return Pasajero::create([
            'nombres' => 'Ana', 'apellidos' => 'Pérez', 'tipo_documento' => 'cedula',
            'numero_documento' => '1712345678', 'tipo_pasajero' => 'nacional',
        ]);
    }
}
