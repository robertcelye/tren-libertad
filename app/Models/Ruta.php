<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Clase Ruta. */
class Ruta extends Model
{
    protected $table = 'rutas';

    protected $fillable = ['codigo', 'nombre', 'estacion_origen', 'estacion_destino', 'distancia_km', 'duracion_min', 'estado'];

    protected function casts(): array
    {
        return ['distancia_km' => 'decimal:2', 'duracion_min' => 'integer'];
    }

    /** Composición Ruta 1 —◆ 0..* Viaje */
    public function viajes(): HasMany
    {
        return $this->hasMany(Viaje::class);
    }

    /** Composición Ruta 1 —◆ 0..* Tarifa */
    public function tarifas(): HasMany
    {
        return $this->hasMany(Tarifa::class);
    }

    /** Ruta 0..* — 0..* Emprendimiento */
    public function emprendimientos(): BelongsToMany
    {
        return $this->belongsToMany(Emprendimiento::class, 'emprendimiento_ruta');
    }

    public function crearViaje(string $fecha, string $horaSalida, int $numVagones, int $capacidad): Viaje
    {
        return $this->viajes()->create([
            'fecha' => $fecha,
            'hora_salida' => $horaSalida,
            'num_vagones' => $numVagones,
            'capacidad' => $capacidad,
        ]);
    }

    /** Tarifa vigente para un tipo de pasajero en una fecha. */
    public function tarifaVigente(string $tipoPasajero, ?string $fecha = null): ?Tarifa
    {
        return $this->tarifas()->vigentes($fecha)->where('tipo_pasajero', $tipoPasajero)->latest('fecha_inicio')->first();
    }
}
