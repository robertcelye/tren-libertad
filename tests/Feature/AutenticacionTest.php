<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolSeeder;
use Database\Seeders\UsuarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** HU-03: registrarse e iniciar sesión. Cada prueba corresponde a un criterio de aceptación. */
class AutenticacionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolSeeder::class, UsuarioSeeder::class]);
    }

    public function test_el_turista_se_registra_con_rol_turista_por_defecto(): void
    {
        $respuesta = $this->post('/registro', [
            'name' => 'Ana', 'apellidos' => 'Pérez', 'email' => 'ana@correo.com',
            'password' => 'Clave1234', 'password_confirmation' => 'Clave1234', 'acepta_datos' => '1',
        ]);

        $respuesta->assertRedirect(route('panel'));
        $this->assertAuthenticated();
        $this->assertTrue(User::where('email', 'ana@correo.com')->first()->tieneRol('turista'));
    }

    public function test_el_registro_exige_aceptar_el_tratamiento_de_datos(): void
    {
        $this->post('/registro', [
            'name' => 'Ana', 'apellidos' => 'Pérez', 'email' => 'ana@correo.com',
            'password' => 'Clave1234', 'password_confirmation' => 'Clave1234',
        ])->assertSessionHasErrors('acepta_datos');

        $this->assertGuest();
    }

    public function test_un_usuario_registrado_inicia_sesion(): void
    {
        $this->post('/ingresar', ['email' => 'turista@tren.test', 'password' => 'password'])
            ->assertRedirect(route('panel'));

        $this->assertAuthenticated();
    }

    public function test_credenciales_incorrectas_niegan_el_acceso(): void
    {
        $this->post('/ingresar', ['email' => 'turista@tren.test', 'password' => 'otra-clave'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_un_usuario_inactivo_no_puede_ingresar(): void
    {
        User::where('email', 'turista@tren.test')->update(['activo' => false]);

        $this->post('/ingresar', ['email' => 'turista@tren.test', 'password' => 'password']);

        $this->assertGuest();
    }

    public function test_el_panel_exige_sesion(): void
    {
        $this->get('/panel')->assertRedirect(route('login'));
    }
}
