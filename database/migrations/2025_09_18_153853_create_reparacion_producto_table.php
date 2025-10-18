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
        Schema::create('reparacion_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reparacion_id');
            $table->unsignedBigInteger('producto_id');
            $table->decimal('precio', 10, 2)->nullable();
            $table->integer('cantidad')->default(1);
            $table->timestamps();

            // Llaves foráneas
            $table->foreign('reparacion_id')
                  ->references('id')
                  ->on('reparaciones')
                  ->onDelete('cascade');

            $table->foreign('producto_id')
                  ->references('id')
                  ->on('productos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reparacion_producto');
    }
};
