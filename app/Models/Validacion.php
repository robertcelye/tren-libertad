<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Clase Validacion: cada intento de abordaje, válido o no (RN-13, RN-14). */
class Validacion extends Model
{
    protected $table = 'validaciones';

    protected $fillable = ['boleto_id', 'user_id', 'fecha_hora', 'resultado', 'modo', 'dispositivo', 'sincronizado', 'fecha_sincronizacion'];

    protected function casts(): array
    {
        return [
            'fecha_hora' => 'datetime',
            'fecha_sincronizacion' => 'datetime',
            'sincronizado' => 'boolean',
        ];
    }

    public function boleto(): BelongsTo
    {
        return $this->belongsTo(Boleto::class);
    }

    /** Personal de control que validó. */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // validarQR(), registrarAbordaje(), guardarSinConexion() y sincronizar() se implementan en la HU-19 y la HU-20.
}
