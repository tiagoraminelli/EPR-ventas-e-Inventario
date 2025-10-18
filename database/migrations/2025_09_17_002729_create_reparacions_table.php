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
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_unico', 50)->unique();
            $table->unsignedBigInteger('cliente_id');
            $table->string('equipo_descripcion', 255);
            $table->string('equipo_marca', 100)->nullable();
            $table->string('equipo_modelo', 100)->nullable();
            $table->text('descripcion_danio');
            $table->text('solucion_aplicada')->nullable();
            $table->boolean('reparable')->default(true);
            $table->enum('estado_reparacion', ['Pendiente', 'En proceso', 'Reparado', 'No reparable', 'Entregado'])->default('Pendiente');
            $table->timestamps();
            $table->softDeletes(); // Para el borrado suave

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reparaciones');
    }
};
