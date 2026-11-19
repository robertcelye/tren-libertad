<?php

namespace Tests\Feature;

use App\Models\RegistroAuditoria;
use App\Models\Rol;
use App\Models\User;
use Database\Seeders\RolSeeder;
use Database\Seeders\UsuarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** HU-04 (cuentas de personal) y HU-05 (permisos por rol). */
class UsuariosYPermisosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolSeeder::class, UsuarioSeeder::class]);
    }

    private function usuario(string $rol): User
    {
        return User::where('email', $rol.'@tren.test')->first();
    }

    // ---------- HU-04 ----------

    public function test_el_administrador_crea_una_cuenta_de_personal_con_rol(): void
    {
        $rol = Rol::where('slug', 'personal_control')->first();

        $this->actingAs($this->usuario('administrador_sistema'))->post('/usuarios', [
            'name' => 'Luis', 'apellidos' => 'Andrade', 'email' => 'luis@tren.test', 'rol_id' => $rol->id,
            'password' => 'Clave1234', 'password_confirmation' => 'Clave1234',
        ])->assertRedirect(route('usuarios.index'));

        $nuevo = User::where('email', 'luis@tren.test')->first();
        $this->assertTrue($nuevo->tieneRol('personal_control'));
        $this->assertTrue(RegistroAuditoria::where('operacion', 'crear_cuenta_personal')->exists());
    }

    public function test_no_se_puede_crear_personal_con_rol_turista(): void
    {
        $this->actingAs($this->usuario('administrador_sistema'))->post('/usuarios', [
            'name' => 'X', 'apellidos' => 'Y', 'email' => 'x@tren.test',
            'rol_id' => Rol::where('slug', 'turista')->value('id'),
            'password' => 'Clave1234', 'password_confirmation' => 'Clave1234',
        ])->assertSessionHasErrors('rol_id');
    }

    public function test_un_turista_no_accede_a_la_creacion_de_cuentas(): void
    {
        $this->actingAs($this->usuario('turista'))->get('/usuarios/crear')->assertForbidden();

        $this->assertTrue(RegistroAuditoria::where('operacion', 'acceso_denegado')->exists());
    }

    // ---------- HU-05 ----------

    public function test_el_administrador_de_seguridad_cambia_los_permisos_de_un_rol(): void
    {
        $rol = Rol::where('slug', 'gestor_institucional')->first();

        $this->actingAs($this->usuario('administrador_seguridad'))
            ->put("/roles/{$rol->id}", ['permisos' => ['reportes.ver']])
            ->assertRedirect(route('roles.index'));

        $this->assertSame(['reportes.ver'], $rol->fresh()->permisos);
        $this->assertTrue(RegistroAuditoria::where('operacion', 'cambiar_permisos_rol')->exists());
    }

    public function test_al_quitar_un_permiso_se_bloquea_el_acceso(): void
    {
        $admin = $this->usuario('administrador_sistema');
        $this->actingAs($admin)->get('/usuarios')->assertOk();

        $admin->rol->update(['permisos' => ['viajes.ver']]);

        $this->actingAs($admin->fresh())->get('/usuarios')->assertForbidden();
    }

    public function test_el_rol_de_seguridad_no_pierde_su_propio_permiso(): void
    {
        $rol = Rol::where('slug', 'administrador_seguridad')->first();

        $this->actingAs($this->usuario('administrador_seguridad'))
            ->put("/roles/{$rol->id}", ['permisos' => ['auditoria.ver']]);

        $this->assertContains('roles.permisos', $rol->fresh()->permisos);
    }

    public function test_un_usuario_desactivado_no_puede_ingresar(): void
    {
        $control = $this->usuario('personal_control');

        $this->actingAs($this->usuario('administrador_sistema'))->patch("/usuarios/{$control->id}/estado");
        $this->assertFalse($control->fresh()->activo);

        auth()->logout();
        $this->post('/ingresar', ['email' => $control->email, 'password' => 'password']);
        $this->assertGuest();
    }
}
