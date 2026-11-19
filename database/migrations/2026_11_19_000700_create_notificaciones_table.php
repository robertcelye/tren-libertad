<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Notificacion. Reserva 1 — 0..*, Boleto 1 — 0..*.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->nullable()->constrained('reservas')->cascadeOnDelete();
            $table->foreignId('boleto_id')->nullable()->constrained('boletos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo', 30);                           // confirmacion | boleto | recordatorio | recuperacion | cambio_viaje
            $table->string('destinatario');
            $table->string('asunto');
            $table->text('mensaje');
            $table->string('canal', 20)->default('correo');       // correo | mensajeria
            $table->timestamp('fecha_envio')->nullable();
            $table->string('estado_envio', 20)->default('pendiente'); // pendiente | enviado | fallido
            $table->unsignedTinyInteger('intentos')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
