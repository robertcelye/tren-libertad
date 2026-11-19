<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** Clase Emprendimiento: solo los autorizados se publican (RN-21). */
class Emprendimiento extends Model
{
    protected $table = 'emprendimientos';

    protected $fillable = ['user_id', 'nombre', 'tipo', 'descripcion', 'ubicacion', 'contacto', 'estado_autorizacion'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Ruta 0..* — 0..* Emprendimiento */
    public function rutas(): BelongsToMany
    {
        return $this->belongsToMany(Ruta::class, 'emprendimiento_ruta');
    }

    /** Solo los autorizados. Uso: Emprendimiento::autorizados()->get() */
    public function scopeAutorizados(Builder $query): Builder
    {
        return $query->where('estado_autorizacion', 'autorizado');
    }

    public function autorizar(): void
    {
        $this->update(['estado_autorizacion' => 'autorizado']);
    }

    public function vincularRuta(Ruta $ruta): void
    {
        $this->rutas()->syncWithoutDetaching([$ruta->id]);
    }
}
