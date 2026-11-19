<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Promocion y su asociación 0..* — 0..* con Viaje (tabla intermedia).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 8, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        Schema::create('promocion_viaje', function (Blueprint $table) {
            $table->foreignId('promocion_id')->constrained('promociones')->cascadeOnDelete();
            $table->foreignId('viaje_id')->constrained('viajes')->cascadeOnDelete();
            $table->primary(['promocion_id', 'viaje_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promocion_viaje');
        Schema::dropIfExists('promociones');
    }
};
