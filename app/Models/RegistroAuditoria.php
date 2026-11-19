<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Clase RegistroAuditoria: historial de operaciones críticas (RN-14, RN-20). Solo se crea, nunca se edita. */
class RegistroAuditoria extends Model
{
    protected $table = 'registros_auditoria';

    public $timestamps = false;   // usa su propio campo fecha_hora

    protected $fillable = ['user_id', 'operacion', 'entidad', 'id_entidad', 'valor_anterior', 'valor_nuevo', 'resultado', 'direccion_ip', 'fecha_hora'];

    protected function casts(): array
    {
        return [
            'valor_anterior' => 'array',
            'valor_nuevo' => 'array',
            'fecha_hora' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Registra una operación. Ej.: RegistroAuditoria::registrarOperacion('cambiar_tarifa', $tarifa, $antes, $despues) */
    public static function registrarOperacion(string $operacion, Model $entidad, ?array $antes = null, ?array $despues = null, string $resultado = 'exitoso'): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'operacion' => $operacion,
            'entidad' => class_basename($entidad),
            'id_entidad' => (string) $entidad->getKey(),
            'valor_anterior' => $antes,
            'valor_nuevo' => $despues,
            'resultado' => $resultado,
            'direccion_ip' => request()?->ip(),
            'fecha_hora' => now(),
        ]);
    }
}
