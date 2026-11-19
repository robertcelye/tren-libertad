<?php

namespace Tests\Feature;

use App\Models\RegistroAuditoria;
use App\Models\Ruta;
use App\Models\User;
use App\Models\Viaje;
use Database\Seeders\RolSeeder;
use Database\Seeders\UsuarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** HU-06 (rutas y frecuencias), HU-07 (vagones y capacidad) y HU-08 (tarifas). */
class OfertaTest extends TestCase
{
    use RefreshDatabase;

    private Ruta $ruta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolSeeder::class, UsuarioSeeder::class]);

        $this->ruta = Ruta::create([
            'codigo' => 'TEST-01', 'nombre' => 'Ruta de prueba',
            'estacion_origen' => 'Ibarra', 'estacion_destino' => 'Salinas', 'estado' => 'activa',
        ]);
    }

    private function usuario(string $rol): User
    {
        return User::where('email', $rol.'@tren.test')->first();
    }

    // ---------- HU-06 ----------

    public function test_el_operador_crea_una_ruta(): void
    {
        $this->actingAs($this->usuario('operador_ferroviario'))->post('/gestion/rutas', [
            'codigo' => 'IBA-ESP', 'nombre' => 'Ibarra – El Esparragal',
            'estacion_origen' => 'Ibarra', 'estacion_destino' => 'El Esparragal', 'estado' => 'activa',
        ])->assertRedirect(route('rutas.index'));

        $this->assertDatabaseHas('rutas', ['codigo' => 'IBA-ESP']);
    }

    public function test_programar_viajes_respeta_los_dias_elegidos(): void
    {
        $lunes = now()->next('Monday');

        // Dos semanas, solo sábado (6) y domingo (0) → 4 viajes.
        $this->actingAs($this->usuario('operador_ferroviario'))->post("/gestion/rutas/{$this->ruta->id}/viajes", [
            'fecha_inicio' => $lunes->toDateString(),
            'fecha_fin' => $lunes->copy()->addDays(13)->toDateString(),
            'dias' => [6, 0],
            'hora_salida' => '08:00', 'hora_llegada' => '10:00',
            'num_vagones' => 2, 'asientos_por_vagon' => 40,
        ])->assertRedirect(route('viajes.index', $this->ruta));

        $viajes = $this->ruta->viajes;
        $this->assertCount(4, $viajes);
        $this->assertTrue($viajes->every(fn (Viaje $v) => $v->fecha->isWeekend() && $v->capacidad === 80));
    }

    public function test_programar_dos_veces_no_duplica_viajes(): void
    {
        $datos = [
            'fecha_inicio' => today()->toDateString(), 'fecha_fin' => today()->addDays(6)->toDateString(),
            'dias' => [0, 1, 2, 3, 4, 5, 6], 'hora_salida' => '08:00',
            'num_vagones' => 1, 'asientos_por_vagon' => 40,
        ];
        $operador = $this->usuario('operador_ferroviario');

        $this->actingAs($operador)->post("/gestion/rutas/{$this->ruta->id}/viajes", $datos);
        $this->actingAs($operador)->post("/gestion/rutas/{$this->ruta->id}/viajes", $datos);

        $this->assertCount(7, $this->ruta->viajes);
    }

    // ---------- HU-07 ----------

    public function test_la_capacidad_se_calcula_por_vagones(): void
    {
        $viaje = $this->ruta->crearViaje(today()->addDay()->toDateString(), '08:00:00', 1, 40);

        $this->actingAs($this->usuario('operador_ferroviario'))
            ->put("/gestion/viajes/{$viaje->id}/capacidad", ['num_vagones' => 3, 'asientos_por_vagon' => 40]);

        $this->assertSame(120, $viaje->fresh()->capacidad);
    }

    public function test_la_capacidad_no_baja_de_lo_vendido(): void
    {
        $viaje = $this->ruta->crearViaje(today()->addDay()->toDateString(), '08:00:00', 2, 80);
        $viaje->update(['cupos_vendidos' => 50]);

        $this->actingAs($this->usuario('operador_ferroviario'))
            ->put("/gestion/viajes/{$viaje->id}/capacidad", ['num_vagones' => 1, 'asientos_por_vagon' => 40])
            ->assertSessionHas('error');

        $this->assertSame(80, $viaje->fresh()->capacidad);
    }

    // ---------- HU-08 ----------

    public function test_una_tarifa_futura_rige_desde_su_fecha(): void
    {
        $admin = $this->usuario('administrador_sistema');

        $this->actingAs($admin)->post("/gestion/rutas/{$this->ruta->id}/tarifas", [
            'tipo_pasajero' => 'nacional', 'valor' => 20, 'cargo_servicio' => 1.5,
            'fecha_inicio' => today()->toDateString(),
        ]);
        $this->actingAs($admin)->post("/gestion/rutas/{$this->ruta->id}/tarifas", [
            'tipo_pasajero' => 'nacional', 'valor' => 25, 'cargo_servicio' => 1.5,
            'fecha_inicio' => today()->addDays(10)->toDateString(),
        ]);

        $this->assertSame('21.50', $this->ruta->tarifaVigente('nacional')->calcularPrecio());
        $this->assertSame('26.50', $this->ruta->tarifaVigente('nacional', today()->addDays(10)->toDateString())->calcularPrecio());
        $this->assertTrue(RegistroAuditoria::where('operacion', 'cambiar_tarifa')->exists());
    }

    public function test_el_operador_ferroviario_no_puede_cambiar_tarifas(): void
    {
        $this->actingAs($this->usuario('operador_ferroviario'))
            ->get("/gestion/rutas/{$this->ruta->id}/tarifas")
            ->assertForbidden();
    }

    public function test_un_turista_no_accede_a_la_gestion_de_rutas(): void
    {
        $this->actingAs($this->usuario('turista'))->get('/gestion/rutas')->assertForbidden();
    }
}
