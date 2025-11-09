<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Paciente de prueba (ID 1)
        User::create([
            'name' => 'Paciente Juan Pérez',
            'email' => 'paciente@test.com',
            'password' => Hash::make('password'),
            'fecha_nacimiento' => '1990-05-15',
            'sexo' => 'Masculino',
            'numero_seguro' => '1234567890',
            'contacto_emergencia' => '555-1234',
        ]);

        // Usuario que será Doctor (ID 2)
        User::create([
            'name' => 'Doctora Ana García',
            'email' => 'doctora@test.com',
            'password' => Hash::make('password'),
            'fecha_nacimiento' => '1985-11-20',
            'sexo' => 'Femenino',
            'numero_seguro' => null, 
            'contacto_emergencia' => '555-5678',
        ]);

        // Crea 8 usuarios más de prueba usando la factoría
        // Nota: Asegúrate de tener una UserFactory en database/factories/UserFactory.php
        // User::factory(8)->create(); 
    }
}
// Fin de database/seeders/UsuarioSeeder.php