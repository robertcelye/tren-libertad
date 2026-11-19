<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Clase Viaje: una salida concreta de una ruta. Guarda la capacidad y los cupos (RN-01, RN-02). */
class Viaje extends Model
{
    protected $table = 'viajes';

    protected $fillable = ['ruta_id', 'fecha', 'hora_salida', 'hora_llegada', 'num_vagones', 'capacidad',
        'cupos_reservados', 'cupos_vendidos', 'estado'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'num_vagones' => 'integer',
            'capacidad' => 'integer',
            'cupos_reservados' => 'integer',
            'cupos_vendidos' => 'integer',
        ];
    }

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class);
    }

    /** Viaje 1 — 0..* Reserva */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    /** Viaje 1 — 0..* Boleto */
    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    /** Viaje 1 — 0..* Incidencia */
    public function incidencias(): HasMany
    {
        return $this->hasMany(Incidencia::class);
    }

    /** Viaje 0..* — 0..* Promocion */
    public function promociones(): BelongsToMany
    {
        return $this->belongsToMany(Promocion::class, 'promocion_viaje');
    }

    /** Solo viajes programados desde hoy. Uso: Viaje::disponibles()->get() */
    public function scopeDisponibles(Builder $query): Builder
    {
        return $query->where('estado', 'programado')->whereDate('fecha', '>=', now()->toDateString());
    }

    /** Cupos libres = capacidad − reservados − vendidos. */
    public function consultarDisponibilidad(): int
    {
        return max(0, $this->capacidad - $this->cupos_reservados - $this->cupos_vendidos);
    }

    public function estaLleno(): bool
    {
        return $this->consultarDisponibilidad() === 0;
    }

    /** Capacidad del viaje según los vagones asignados (RF-05.02). */
    public function asignarCapacidad(int $numVagones, int $asientosPorVagon): void
    {
        $this->num_vagones = $numVagones;
        $this->capacidad = $numVagones * $asientosPorVagon;
        $this->save();
    }

    // reservarCupos() y liberarCupos() se implementan en la HU-10, con bloqueo de fila para evitar sobreventa.
}
