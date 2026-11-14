<?php

namespace Database\Seeders;

use App\Models\Oficina;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear oficinas base si no existen
        $soporte = Oficina::firstOrCreate(
            ['nombre' => 'Soporte Técnico'],
            ['ubicacion' => 'Planta Baja - Informática']
        );

        $sistemas = Oficina::firstOrCreate(
            ['nombre' => 'Sistemas'],
            ['ubicacion' => 'Piso 1 - Informática']
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@sigedtec.test'],
            [
                'nombres'    => 'Admin',
                'apellidos'  => 'Soporte',
                'cedula'     => '12345678',
                'oficina_id' => $soporte->id,
                'role'       => User::ROLE_ADMIN,
                'password'   => Hash::make('Admin123!'),
            ]
        );

        // Solicitante
        User::updateOrCreate(
            ['email' => 'solicitante@sigedtec.test'],
            [
                'nombres'    => 'Juan',
                'apellidos'  => 'Pérez',
                'cedula'     => '87654321',
                'oficina_id' => $sistemas->id,
                'role'       => User::ROLE_SOLICITANTE,
                'password'   => Hash::make('Sol12345!'),
            ]
        );
    }
}