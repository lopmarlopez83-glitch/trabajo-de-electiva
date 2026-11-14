<?php

namespace Database\Factories;

use App\Models\Equipo;
use App\Models\Modelo;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipoFactory extends Factory
{
    protected $model = Equipo::class;

    public function definition(): array
    {
        return [
            'serial'             => strtoupper($this->faker->unique()->bothify('SN-####-????')),
            'codigo_patrimonial' => strtoupper($this->faker->unique()->bothify('PAT-#####')),
            'modelo_id'          => Modelo::factory(),
            'status'             => $this->faker->randomElement([
                Equipo::STATUS_DISPONIBLE,
                Equipo::STATUS_EN_MANTENIMIENTO,
            ]),
            'observaciones'      => $this->faker->optional()->sentence(),
        ];
    }

    public function disponible(): static
    {
        return $this->state(fn () => ['status' => Equipo::STATUS_DISPONIBLE]);
    }
}