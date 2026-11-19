<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Clase Notificacion: registro de cada envío (confirmación, boleto, recordatorio...). */
class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = ['reserva_id', 'boleto_id', 'user_id', 'tipo', 'destinatario', 'asunto', 'mensaje', 'canal',
        'fecha_envio', 'estado_envio', 'intentos'];

    protected function casts(): array
    {
        return ['fecha_envio' => 'datetime', 'intentos' => 'integer'];
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    public function boleto(): BelongsTo
    {
        return $this->belongsTo(Boleto::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registrarEstado(string $estado): void
    {
        $this->update([
            'estado_envio' => $estado,
            'fecha_envio' => $estado === 'enviado' ? now() : $this->fecha_envio,
            'intentos' => $this->intentos + 1,
        ]);
    }

    // enviarConfirmacion(), enviarBoleto() y enviarRecordatorio() usan el AdaptadorNotificaciones en la HU-25.
}
