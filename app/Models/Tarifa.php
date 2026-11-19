<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Clase Tarifa: USD 20 nacionales, USD 40 extranjeros, más cargos (sección 4.2). */
class Tarifa extends Model
{
    protected $table = 'tarifas';

    protected $fillable = ['ruta_id', 'tipo_pasajero', 'valor', 'cargo_servicio', 'moneda', 'fecha_inicio', 'fecha_fin', 'activa'];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'cargo_servicio' => 'decimal:2',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'activa' => 'boolean',
        ];
    }

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class);
    }

    /** Tarifas activas cuya vigencia incluye la fecha. */
    public function scopeVigentes(Builder $query, ?string $fecha = null): Builder
    {
        $fecha ??= now()->toDateString();

        return $query->where('activa', true)
            ->whereDate('fecha_inicio', '<=', $fecha)
            ->where(fn (Builder $q) => $q->whereNull('fecha_fin')->orWhereDate('fecha_fin', '>=', $fecha));
    }

    public function verificarVigencia(?string $fecha = null): bool
    {
        $fecha = $fecha ? \Illuminate\Support\Carbon::parse($fecha) : now();

        return $this->activa && $this->fecha_inicio->lte($fecha) && ($this->fecha_fin === null || $this->fecha_fin->gte($fecha));
    }

    /** Precio final = valor + cargo de servicio. */
    public function calcularPrecio(): string
    {
        return number_format((float) $this->valor + (float) $this->cargo_servicio, 2, '.', '');
    }
}
