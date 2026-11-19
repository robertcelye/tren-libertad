<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Rol: los 11 roles de la sección 8 del caso, con su lista de permisos.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();          // nombre interno: turista, operador_boleteria...
            $table->string('nombre');                  // nombre visible: Turista / Visitante
            $table->string('descripcion')->nullable();
            $table->json('permisos')->nullable();      // List<String> del diagrama de clases
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
