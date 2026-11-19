<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Incidencia. Viaje 1 — 0..* Incidencia, Usuario 1 — 0..* Incidencia.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viaje_id')->constrained('viajes')->cascadeOnDelete();
            $table->foreignId('boleto_id')->nullable()->constrained('boletos')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // operador ferroviario
            $table->string('tipo', 30);                           // retraso | cancelacion | falla_tecnica | abordaje | pago
            $table->text('descripcion');
            $table->timestamp('fecha_hora');
            $table->string('estado', 20)->default('abierta');     // abierta | cerrada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
