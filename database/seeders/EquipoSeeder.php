<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Database\Seeder;

class EquipoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear catálogo base si no existe
        $categorias = collect(['Laptop', 'Monitor', 'Impresora', 'Video Beam', 'Ratón'])
            ->map(fn ($n) => Categoria::firstOrCreate(['nombre' => $n], ['descripcion' => "Equipos de tipo {$n}"]));

        $marcas = collect(['HP', 'Dell', 'Lenovo', 'Epson', 'Logitech'])
            ->map(fn ($n) => Marca::firstOrCreate(['nombre' => $n]));

        // Crear 8 modelos combinando
        $modelos = collect();
        foreach ($categorias as $cat) {
            foreach ($marcas->random(2) as $marca) {
                $modelos->push(Modelo::firstOrCreate([
                    'nombre'       => "{$cat->nombre} {$marca->nombre} " . fake()->numberBetween(100, 999),
                    'marca_id'     => $marca->id,
                    'categoria_id' => $cat->id,
                ]));
            }
        }

        // Crear 20+ equipos distribuidos
        Equipo::factory()
            ->count(20)
            ->recycle($modelos)
            ->create();
    }
}