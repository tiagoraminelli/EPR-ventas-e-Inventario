<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id(); // id primaria
            $table->string('nombre'); // Nombre del servicio, ej: Instalación de S.O.
            $table->text('descripcion')->nullable(); // Descripción del servicio
            $table->decimal('iva_aplicable', 10, 2)->nullable(); // IVA aplicable al servicio
            $table->decimal('precio', 10, 2)->default(0); // Precio del servicio
            $table->boolean('activo')->default(true); // Si el servicio está activo
            $table->boolean('visible')->default(true); // Si el servicio está visible
            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
