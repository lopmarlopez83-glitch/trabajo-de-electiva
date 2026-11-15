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

        // ✅ Admin (nuevas credenciales)
        User::updateOrCreate(
            ['email' => 'sebas@gmail.com'],
            [
                'nombres'    => 'Sebastian',
                'apellidos'  => 'Lopez',
                'cedula'     => '12345678',
                'oficina_id' => $soporte->id,
                'role'       => User::ROLE_ADMIN,
                'password'   => Hash::make('12345678'),
            ]
        );

        // ✅ Solicitante (nuevas credenciales)
        User::updateOrCreate(
            ['email' => 'sofi@gmail.com'],
            [
                'nombres'    => 'Sofia',
                'apellidos'  => 'Lopez',
                'cedula'     => '87654321',
                'oficina_id' => $sistemas->id,
                'role'       => User::ROLE_SOLICITANTE,
                'password'   => Hash::make('12345678'),
            ]
        );
    }
}