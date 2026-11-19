<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Tarifa. Composición Ruta 1 —◆ 0..* Tarifa.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained('rutas')->cascadeOnDelete();
            $table->string('tipo_pasajero', 20);                 // nacional | extranjero
            $table->decimal('valor', 8, 2);
            $table->decimal('cargo_servicio', 8, 2)->default(0);
            $table->string('moneda', 3)->default('USD');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};
