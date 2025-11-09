<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HistoriaMedica;
use App\Models\Tratamiento;
use App\Models\User;
use App\Models\Doctor;

class HistoriaMedicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el ID del paciente de prueba (Paciente Juan Pérez, ID 1)
        $paciente = User::where('email', 'paciente@test.com')->first();
        
        // Obtener el perfil del doctor de prueba (Doctora Ana García)
        $doctorUser = User::where('email', 'doctora@test.com')->first();
        $doctor = Doctor::where('user_id', $doctorUser->id)->first();

        if ($paciente && $doctor) {
            // 1. CREAR UNA HISTORIA MÉDICA
            $historia = HistoriaMedica::create([
                'paciente_id' => $paciente->id,
                'doctor_id' => $doctor->id,
                'fecha_registro' => '2025-10-20',
                'sintomas' => 'Fiebre persistente durante 3 días, tos seca y dolor de garganta. No hay dificultad respiratoria.',
                'diagnostico' => 'Faringitis viral, se recomienda reposo y tratamiento sintomático.',
            ]);

            // 2. AÑADIR UN TRATAMIENTO A ESTA HISTORIA
            Tratamiento::create([
                'historia_id' => $historia->id,
                'nombre' => 'Acetaminofén (Paracetamol)',
                'instrucciones_dosis' => '500mg cada 6 horas según dolor o fiebre.',
                'fecha_inicio' => '2025-10-20',
                'fecha_fin_estimada' => '2025-10-25',
                'activo' => true,
            ]);
            
            // Puedes añadir más historias o tratamientos si lo deseas
        }
    }
}
// Fin de database/seeders/HistoriaMedicaSeeder.php