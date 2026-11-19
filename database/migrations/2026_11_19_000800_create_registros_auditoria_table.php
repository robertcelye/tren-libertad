<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase RegistroAuditoria (RN-14, RN-20). Usuario 1 — 0..* RegistroAuditoria.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('operacion', 60);                      // crear_reserva, cambiar_tarifa, validar_boleto...
            $table->string('entidad', 60);                        // Reserva, Tarifa, Boleto...
            $table->string('id_entidad', 60)->nullable();
            $table->json('valor_anterior')->nullable();
            $table->json('valor_nuevo')->nullable();
            $table->string('resultado', 20)->default('exitoso');  // exitoso | fallido
            $table->string('direccion_ip', 45)->nullable();
            $table->timestamp('fecha_hora')->useCurrent();

            $table->index(['entidad', 'id_entidad']);
            $table->index('fecha_hora');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_auditoria');
    }
};
