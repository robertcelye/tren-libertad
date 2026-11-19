<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Clase Usuario del diagrama.
 * Se mantiene el nombre User porque el sistema de autenticación de Laravel lo espera así.
 * Equivalencias: name = nombres, email = correo, password = contraseña (hash).
 */
#[Fillable(['name', 'apellidos', 'username', 'email', 'telefono', 'password', 'activo', 'rol_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    // ---------- Relaciones (diagrama de clases) ----------

    /** Rol 1 — 0..* Usuario */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class);
    }

    /** Usuario 1 — 0..* Validacion (personal de control) */
    public function validaciones(): HasMany
    {
        return $this->hasMany(Validacion::class);
    }

    /** Usuario 1 — 0..* Incidencia (operador ferroviario) */
    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class);
    }

    /** Usuario 1 — 0..* RegistroAuditoria */
    public function registrosAuditoria(): HasMany
    {
        return $this->hasMany(RegistroAuditoria::class);
    }

    // ---------- Responsabilidades (tarjeta CRC) ----------

    /** ¿El usuario tiene este rol? Ej.: $user->tieneRol('administrador_sistema') */
    public function tieneRol(string ...$slugs): bool
    {
        return $this->rol !== null && in_array($this->rol->slug, $slugs, true);
    }

    /** ¿El rol del usuario permite esta operación? Ej.: $user->verificarPermiso('tarifas.gestionar') */
    public function verificarPermiso(string $operacion): bool
    {
        return $this->activo && $this->rol !== null && $this->rol->permite($operacion);
    }

    /** Nombre completo para mostrar en pantalla. */
    public function getNombreCompletoAttribute(): string
    {
        return trim($this->name.' '.$this->apellidos);
    }
}
