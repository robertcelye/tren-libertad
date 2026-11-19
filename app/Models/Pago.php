<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Clase Pago. Solo guarda la referencia y la autorización de la pasarela, nunca datos de tarjeta. */
class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = ['reserva_id', 'referencia', 'monto', 'moneda', 'metodo_pago', 'codigo_autorizacion', 'fecha_hora', 'estado'];

    protected function casts(): array
    {
        return ['monto' => 'decimal:2', 'fecha_hora' => 'datetime'];
    }

    /** Reserva 1 — 0..1 Pago */
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    public function consultarEstado(): string
    {
        return $this->estado;
    }

    public function estaAprobado(): bool
    {
        return $this->estado === 'aprobado';
    }

    // solicitarCobro() y registrarRespuesta() usan el AdaptadorPasarela en la HU-14.
}
