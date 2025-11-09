<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Perfil para Doctora Ana García
        $userDoctor1 = User::where('email', 'doctora@test.com')->first();

        if ($userDoctor1) {
            Doctor::create([
                'user_id' => $userDoctor1->id,
                'numero_licencia' => 'LIC-123456',
                'especialidad' => 'Cardiología',
                'dias_disponibles' => json_encode([
                    'Lunes' => '08:00-12:00',
                    'Miércoles' => '14:00-18:00'
                ]),
            ]);
        }

        // 2. Crear un segundo Doctor (asumiendo que tienes una factoría funcional)
        $userDoctor2 = User::factory()->create([
            'name' => 'Dr. Carlos Ruiz',
            'email' => 'carlos.ruiz@test.com',
        ]);

        Doctor::create([
            'user_id' => $userDoctor2->id,
            'numero_licencia' => 'LIC-789012',
            'especialidad' => 'Pediatría',
            'dias_disponibles' => json_encode([
                'Martes' => '09:00-13:00',
                'Jueves' => '14:00-17:00'
            ]),
        ]);
    }
}
// Fin de database/seeders/DoctorSeeder.php