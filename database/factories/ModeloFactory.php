<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModeloFactory extends Factory
{
    protected $model = Modelo::class;

    public function definition(): array
    {
        return [
            'nombre'       => $this->faker->bothify('Modelo-###??'),
            'marca_id'     => Marca::factory(),
            'categoria_id' => Categoria::factory(),
        ];
    }
}