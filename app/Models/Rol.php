<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Clase Rol: uno de los 11 roles de la sección 8 del caso. */
class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = ['slug', 'nombre', 'descripcion', 'permisos'];

    protected function casts(): array
    {
        return ['permisos' => 'array'];
    }

    /** Rol 1 — 0..* Usuario */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** ¿Este rol permite la operación? El permiso '*' da acceso total. */
    public function permite(string $operacion): bool
    {
        $permisos = $this->permisos ?? [];

        return in_array('*', $permisos, true) || in_array($operacion, $permisos, true);
    }

    public function asignarPermiso(string $operacion): void
    {
        $this->permisos = array_values(array_unique([...($this->permisos ?? []), $operacion]));
        $this->save();
    }

    public function listarPermisos(): array
    {
        return $this->permisos ?? [];
    }
}
