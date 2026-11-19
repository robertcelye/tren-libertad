<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** Clase Promocion: paquete que combina viaje y servicios. */
class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = ['nombre', 'descripcion', 'precio', 'fecha_inicio', 'fecha_fin', 'activa'];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'activa' => 'boolean',
        ];
    }

    /** Viaje 0..* — 0..* Promocion */
    public function viajes(): BelongsToMany
    {
        return $this->belongsToMany(Viaje::class, 'promocion_viaje');
    }

    public function verificarVigencia(): bool
    {
        return $this->activa && now()->betweenIncluded($this->fecha_inicio->startOfDay(), $this->fecha_fin->endOfDay());
    }
}
