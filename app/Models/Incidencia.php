<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Clase Incidencia: novedades operativas del viaje (RN-18). */
class Incidencia extends Model
{
    protected $table = 'incidencias';

    protected $fillable = ['viaje_id', 'boleto_id', 'user_id', 'tipo', 'descripcion', 'fecha_hora', 'estado'];

    protected function casts(): array
    {
        return ['fecha_hora' => 'datetime'];
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class);
    }

    public function boleto(): BelongsTo
    {
        return $this->belongsTo(Boleto::class);
    }

    /** Operador ferroviario que la registró. */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
