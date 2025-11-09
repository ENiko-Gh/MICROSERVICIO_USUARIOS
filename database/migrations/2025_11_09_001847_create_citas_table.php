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
        // Se crea la tabla 'citas'
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            
            // Clave Foránea 1: Paciente (Usuario) que reserva la cita
            $table->foreignId('paciente_id')
                  ->constrained('users') // Enlace al ID de la tabla users (el paciente)
                  ->onDelete('cascade')
                  ->comment('ID del paciente que reservó la cita (de la tabla users).');
            
            // Clave Foránea 2: Doctor asignado
            $table->foreignId('doctor_id')
                  ->constrained('doctores') // Enlace al ID de la tabla doctores
                  ->onDelete('cascade')
                  ->comment('ID del doctor asignado.');
                  
            $table->dateTime('fecha_hora')->comment('Fecha y hora exacta de la cita.');
            $table->string('motivo')->comment('Breve descripción del motivo de la cita.');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])
                  ->default('pendiente')
                  ->comment('Estado actual de la cita.');
            
            // Restricción: Un doctor no puede tener dos citas a la misma hora
            $table->unique(['doctor_id', 'fecha_hora']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};