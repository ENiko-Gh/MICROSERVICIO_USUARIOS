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
        // Se crea la tabla 'doctores'
        Schema::create('doctores', function (Blueprint $table) {
            $table->id();
            
            // Clave Foránea: Enlace al usuario base que ya existe (users)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->unique()
                  ->comment('Referencia al ID de la cuenta base de usuario.');
                  
            $table->string('numero_licencia')->unique()->comment('Número de licencia profesional.');
            $table->string('especialidad')->comment('Especialidad médica (ej: Cardiología, Pediatría).');
            $table->json('dias_disponibles')->nullable()->comment('Horarios de trabajo como JSON.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctores');
    }
};