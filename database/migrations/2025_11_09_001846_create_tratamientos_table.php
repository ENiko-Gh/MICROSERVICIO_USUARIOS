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
        // Se crea la tabla 'tratamientos'
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();
            
            // Clave Foránea: Enlace al registro específico de la historia médica
            $table->foreignId('historia_id')
                  ->constrained('historias_medicas')
                  ->onDelete('cascade')
                  ->comment('Registro de historia médica asociado.');

            $table->string('nombre')->comment('Nombre del tratamiento o medicamento.');
            $table->text('instrucciones_dosis')->comment('Instrucciones detalladas de dosis/uso.');
            $table->date('fecha_inicio')->comment('Fecha de inicio del tratamiento.');
            $table->date('fecha_fin_estimada')->nullable()->comment('Fecha de finalización si aplica.');
            $table->boolean('activo')->default(true)->comment('Indica si el tratamiento está en curso.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tratamientos');
    }
};