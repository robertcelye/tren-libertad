<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Validacion. Composición Boleto 1 —◆ 0..* Validacion: se guarda cada intento (RN-13, RN-14).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boleto_id')->constrained('boletos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // personal de control
            $table->timestamp('fecha_hora');
            $table->string('resultado', 20);                      // valido | ya_utilizado | cancelado | invalido
            $table->string('modo', 20)->default('en_linea');      // en_linea | sin_conexion
            $table->string('dispositivo', 100)->nullable();
            $table->boolean('sincronizado')->default(true);
            $table->timestamp('fecha_sincronizacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validaciones');
    }
};
