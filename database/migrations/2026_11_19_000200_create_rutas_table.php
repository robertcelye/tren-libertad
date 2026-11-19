<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Ruta.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre');
            $table->string('estacion_origen');
            $table->string('estacion_destino');
            $table->decimal('distancia_km', 6, 2)->nullable();
            $table->unsignedInteger('duracion_min')->nullable();
            $table->string('estado', 20)->default('activa');   // activa | suspendida
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
