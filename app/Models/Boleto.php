<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/** Clase Boleto: título de viaje único e intransferible (RN-03, RN-04, RN-05). */
class Boleto extends Model
{
    protected $table = 'boletos';

    protected $fillable = ['codigo', 'reserva_id', 'pasajero_id', 'viaje_id', 'codigo_qr', 'precio', 'fecha_emision', 'estado'];

    protected function casts(): array
    {
        return ['precio' => 'decimal:2', 'fecha_emision' => 'datetime'];
    }

    /** Al crear un boleto se le asigna automáticamente un identificador único (UUID). */
    protected static function booted(): void
    {
        static::creating(function (Boleto $boleto) {
            $boleto->codigo ??= (string) Str::uuid();
        });
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    public function pasajero(): BelongsTo
    {
        return $this->belongsTo(Pasajero::class);
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class);
    }

    /** Composición Boleto 1 —◆ 0..* Validacion */
    public function validaciones(): HasMany
    {
        return $this->hasMany(Validacion::class);
    }

    /** Boleto 1 — 0..* Notificacion */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class);
    }

    public function consultarEstado(): string
    {
        return $this->estado;
    }

    public function fueUtilizado(): bool
    {
        return $this->estado === 'utilizado';
    }

    // emitirBoleto(), generarQR(), marcarUtilizado() y cancelarBoleto() se implementan en la HU-15 y la HU-19.
}
