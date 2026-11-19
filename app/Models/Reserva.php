<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/** Clase Reserva: retención temporal de cupos mientras se paga (RN-08). */
class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = ['codigo', 'viaje_id', 'pasajero_id', 'agencia_id', 'user_id', 'cantidad', 'monto_total',
        'canal', 'estado', 'fecha_vencimiento'];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'monto_total' => 'decimal:2',
            'fecha_vencimiento' => 'datetime',
        ];
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class);
    }

    public function pasajero(): BelongsTo
    {
        return $this->belongsTo(Pasajero::class);
    }

    /** Agencia 0..1 — 0..* Reserva */
    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencia::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Composición Reserva 1 —◆ 1..* Boleto */
    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    /** Reserva 1 — 0..1 Pago */
    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class);
    }

    /** Reserva 1 — 0..* Notificacion */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class);
    }

    public function estaVencida(): bool
    {
        return $this->estado === 'iniciada' && $this->fecha_vencimiento !== null && $this->fecha_vencimiento->isPast();
    }

    // iniciarReserva(), confirmarReserva(), vencerReserva() y liberarCupo() se implementan en la HU-10.
}
