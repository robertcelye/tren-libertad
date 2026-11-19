<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Clase Usuario: Laravel ya crea la tabla users (name = nombres, email, password).
// Aquí se agregan los atributos del diagrama y la relación Rol 1 — 0..* Usuario.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellidos')->nullable()->after('name');
            $table->string('username')->nullable()->unique()->after('apellidos');
            $table->string('telefono', 20)->nullable()->after('email');
            $table->boolean('activo')->default(true)->after('password');
            $table->foreignId('rol_id')->nullable()->after('activo')->constrained('roles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rol_id');
            $table->dropColumn(['apellidos', 'username', 'telefono', 'activo']);
        });
    }
};
