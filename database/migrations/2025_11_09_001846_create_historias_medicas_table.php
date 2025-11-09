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
        // Se crea la tabla 'historias_medicas'
        Schema::create('historias_medicas', function (Blueprint $table) {
            $table->id();
            
            // Clave Foránea 1: El paciente es el usuario base (users)
            $table->foreignId('paciente_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Referencia al ID del paciente.');
            
            // Clave Foránea 2: El doctor que hizo el registro
            $table->foreignId('doctor_id')
                  ->nullable()
                  ->constrained('doctores')
                  ->onDelete('set null')
                  ->comment('Doctor que registró la historia.');

            $table->date('fecha_registro')->comment('Fecha de la nota médica.');
            $table->text('sintomas')->comment('Motivo de la consulta y síntomas.');
            $table->text('diagnostico')->nullable()->comment('Diagnóstico médico.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historias_medicas');
    }
};