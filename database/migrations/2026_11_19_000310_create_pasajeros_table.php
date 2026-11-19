<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Pasajero. El número de documento se guarda cifrado (RF-04.04, LOPDP), por eso es text.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasajeros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // si el pasajero tiene cuenta
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipo_documento', 20);                // cedula | pasaporte
            $table->text('numero_documento');                    // cifrado
            $table->string('nacionalidad', 60)->nullable();
            $table->string('tipo_pasajero', 20);                 // nacional | extranjero
            $table->date('fecha_nacimiento')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasajeros');
    }
};
