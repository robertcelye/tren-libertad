<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Pago. Reserva 1 — 0..1 Pago (unique en reserva_id).
// No se guardan datos de tarjeta: solo la referencia y la autorización de la pasarela (sección 12).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->unique()->constrained('reservas')->restrictOnDelete();
            $table->string('referencia', 60)->unique();
            $table->decimal('monto', 10, 2);
            $table->string('moneda', 3)->default('USD');
            $table->string('metodo_pago', 20);                    // tarjeta | transferencia | efectivo
            $table->string('codigo_autorizacion', 60)->nullable();
            $table->timestamp('fecha_hora')->nullable();
            $table->string('estado', 20)->default('pendiente');   // pendiente | aprobado | rechazado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
