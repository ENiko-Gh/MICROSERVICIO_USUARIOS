<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsuarioSeeder;
use Database\Seeders\DoctorSeeder; 
use Database\Seeders\HistoriaMedicaSeeder; // Importar

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UsuarioSeeder::class);
        $this->call(DoctorSeeder::class);
        $this->call(HistoriaMedicaSeeder::class); // Añadir la llamada
    }
}
// Fin de database/seeders/DatabaseSeeder.php