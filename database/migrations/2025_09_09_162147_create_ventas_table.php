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
        // Crea la tabla de ventas
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('tipo_comprobante')->default('Factura');
            $table->string('numero_comprobante')->unique()->nullable();
            $table->string('condicion_pago')->default('Contado');
            $table->decimal('importe_neto', 10, 2);
            $table->decimal('importe_iva', 10, 2);
            $table->decimal('importe_total', 10, 2);
            $table->string('estado_venta')->default('Pagada');
            $table->text('observaciones')->nullable();
            $table->date('fecha_venta');
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Elimina la tabla de ventas
        Schema::dropIfExists('ventas');
    }
};
