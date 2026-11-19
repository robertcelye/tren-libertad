<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Emprendimiento y su asociación 0..* — 0..* con Ruta (tabla intermedia).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emprendimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // emprendedor
            $table->string('nombre');
            $table->string('tipo', 30);                           // gastronomia | artesania | guianza | hospedaje | atractivo
            $table->text('descripcion')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('contacto')->nullable();
            $table->string('estado_autorizacion', 20)->default('pendiente'); // pendiente | autorizado | rechazado (RN-21)
            $table->timestamps();                                 // created_at = fechaRegistro
        });

        Schema::create('emprendimiento_ruta', function (Blueprint $table) {
            $table->foreignId('emprendimiento_id')->constrained('emprendimientos')->cascadeOnDelete();
            $table->foreignId('ruta_id')->constrained('rutas')->cascadeOnDelete();
            $table->primary(['emprendimiento_id', 'ruta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emprendimiento_ruta');
        Schema::dropIfExists('emprendimientos');
    }
};
