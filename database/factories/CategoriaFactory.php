<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition(): array
    {
        return [
            'nombre'      => $this->faker->unique()->randomElement([
                'Laptop', 'Monitor', 'Impresora', 'Video Beam', 'Ratón', 'Teclado', 'Proyector', 'Router',
            ]),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}