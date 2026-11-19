<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Clase Pasajero. El número de documento se cifra automáticamente (RF-04.04, LOPDP). */
class Pasajero extends Model
{
    protected $table = 'pasajeros';

    protected $fillable = ['user_id', 'nombres', 'apellidos', 'tipo_documento', 'numero_documento', 'nacionalidad',
        'tipo_pasajero', 'fecha_nacimiento', 'email', 'telefono'];

    protected $hidden = ['numero_documento'];

    protected function casts(): array
    {
        return [
            'numero_documento' => 'encrypted',   // se guarda cifrado con la APP_KEY del .env
            'fecha_nacimiento' => 'date',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Pasajero 1 — 0..* Reserva */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    /** Pasajero 1 — 0..* Boleto */
    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    public function obtenerTipoPasajero(): string
    {
        return $this->tipo_pasajero;
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombres.' '.$this->apellidos);
    }
}
