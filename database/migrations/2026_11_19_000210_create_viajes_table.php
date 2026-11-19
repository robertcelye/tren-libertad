<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Viaje. Composición Ruta 1 —◆ 0..* Viaje: si se elimina la ruta, se eliminan sus viajes.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained('rutas')->cascadeOnDelete();
            $table->date('fecha');
            $table->time('hora_salida');
            $table->time('hora_llegada')->nullable();
            $table->unsignedSmallInteger('num_vagones')->default(1);
            $table->unsignedInteger('capacidad')->default(0);
            $table->unsignedInteger('cupos_reservados')->default(0);
            $table->unsignedInteger('cupos_vendidos')->default(0);
            $table->string('estado', 20)->default('programado'); // programado | en_curso | finalizado | cancelado
            $table->timestamps();

            $table->index(['fecha', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
