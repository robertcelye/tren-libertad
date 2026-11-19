<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Clase Agencia: intermediario con límite de boletos por viaje (RN-01, RN-02). */
class Agencia extends Model
{
    protected $table = 'agencias';

    protected $fillable = ['user_id', 'razon_social', 'ruc', 'email', 'telefono', 'limite_boletos', 'estado'];

    protected function casts(): array
    {
        return ['limite_boletos' => 'integer'];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Agencia 0..1 — 0..* Reserva */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    /** Boletos que la agencia ya tiene en un viaje (reservas activas). */
    public function cuposUsados(Viaje $viaje): int
    {
        return (int) $this->reservas()
            ->where('viaje_id', $viaje->id)
            ->whereIn('estado', ['iniciada', 'confirmada'])
            ->sum('cantidad');
    }

    /** ¿Puede reservar esta cantidad sin superar su límite? (RF-01.03) */
    public function validarLimite(Viaje $viaje, int $cantidad): bool
    {
        return $this->cuposUsados($viaje) + $cantidad <= $this->limite_boletos;
    }
}
