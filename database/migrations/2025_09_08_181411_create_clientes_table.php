<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('RazonSocial', 100)->nullable();
            $table->string('Nombre_completo', 100)->nullable();
            $table->string('CUIT/DNI', 100)->unique();
            $table->string('Domicilio', 100)->nullable();
            $table->string('Localidad', 100)->nullable();
            $table->string('Detalle', 255)->nullable();
            $table->string('Email')->unique();
            $table->string('Telefono', 50)->nullable();
            $table->enum('Tipo_cliente', ['Persona', 'Empresa'])->default('Persona');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
