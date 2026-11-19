<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Agencia (intermediario autorizado, con límite de boletos por viaje).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // cuenta de la agencia
            $table->string('razon_social');
            $table->string('ruc', 13)->unique();
            $table->string('email')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->unsignedInteger('limite_boletos')->default(10);
            $table->string('estado', 20)->default('activa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agencias');
    }
};
