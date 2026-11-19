<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Boleto. Composición Reserva 1 —◆ 1..* Boleto. El código es un UUID único (RN-03).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->uuid('codigo')->unique();                     // identificador único del boleto (va en el QR)
            $table->foreignId('reserva_id')->constrained('reservas')->cascadeOnDelete();
            $table->foreignId('pasajero_id')->constrained('pasajeros')->restrictOnDelete();
            $table->foreignId('viaje_id')->constrained('viajes')->restrictOnDelete();
            $table->text('codigo_qr')->nullable();                // contenido firmado del QR
            $table->decimal('precio', 8, 2);
            $table->timestamp('fecha_emision')->nullable();
            $table->string('estado', 20)->default('reservado');   // reservado | pagado | emitido | utilizado | cancelado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletos');
    }
};
