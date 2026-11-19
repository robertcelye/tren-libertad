<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Reserva. Viaje 1 — 0..*, Pasajero 1 — 0..*, Agencia 0..1 — 0..*.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->foreignId('viaje_id')->constrained('viajes')->restrictOnDelete();
            $table->foreignId('pasajero_id')->constrained('pasajeros')->restrictOnDelete(); // titular
            $table->foreignId('agencia_id')->nullable()->constrained('agencias')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // quién la registró
            $table->unsignedSmallInteger('cantidad')->default(1);
            $table->decimal('monto_total', 10, 2)->default(0);
            $table->string('canal', 20);                          // web | ventanilla | agencia
            $table->string('estado', 20)->default('iniciada');    // iniciada | confirmada | vencida | cancelada
            $table->timestamp('fecha_vencimiento')->nullable();   // reserva temporal (RN-08)
            $table->timestamps();                                 // created_at = fechaCreacion

            $table->index(['estado', 'fecha_vencimiento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
