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
        $categorias = collect(['Laptop', 'Computadora de mesa', 'Monitor', 'Impresora', 'Video Beam', 'Ratón', 'Teclado'])
            ->map(fn ($n) => Categoria::firstOrCreate(
                ['nombre' => $n],
                ['descripcion' => "Equipos de tipo {$n}"]
            ));

        $marcas = collect(['HP', 'Dell', 'Lenovo', 'Epson', 'Logitech'])
            ->map(fn ($n) => Marca::firstOrCreate(['nombre' => $n]));

        // Crear modelos
        $modelos = collect();
        foreach ($categorias as $cat) {
            foreach ($marcas->random(2) as $marca) {
                $nombreMod = "{$cat->nombre} {$marca->nombre} " . fake()->numberBetween(100, 999);
                $modelos->push(Modelo::firstOrCreate([
                    'nombre'       => $nombreMod,
                    'marca_id'     => $marca->id,
                    'categoria_id' => $cat->id,
                ]));
            }
        }

        // Crear 25 equipos
        foreach ($modelos as $modelo) {
            $esPC = in_array(strtolower($modelo->categoria->nombre), ['laptop', 'computadora de mesa']);
            
            Equipo::create([
                'serial'         => strtoupper(fake()->unique()->bothify('SN-####-????')),
                'modelo_id'      => $modelo->id,
                'cpu'            => $esPC ? fake()->randomElement(['Intel Core i5', 'Intel Core i7', 'AMD Ryzen 5', 'AMD Ryzen 7']) : null,
                'ram'            => $esPC ? fake()->randomElement(['8GB', '16GB', '32GB']) : null,
                'almacenamiento' => $esPC ? fake()->randomElement(['256GB SSD', '512GB SSD', '1TB HDD', '1TB SSD']) : null,
                'status'         => fake()->randomElement([
                    Equipo::STATUS_DISPONIBLE,
                    Equipo::STATUS_EN_MANTENIMIENTO,
                ]),
                'observaciones'  => fake()->optional()->sentence(),
            ]);
        }
    }
}