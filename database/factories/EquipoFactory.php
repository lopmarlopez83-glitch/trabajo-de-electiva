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
            'serial'        => strtoupper($this->faker->unique()->bothify('SN-####-????')),
            'modelo_id'     => Modelo::factory(),
            'cpu'           => $this->faker->optional(0.6)->randomElement(['Intel Core i5', 'Intel Core i7', 'AMD Ryzen 5', 'AMD Ryzen 7']),
            'ram'           => $this->faker->optional(0.6)->randomElement(['8GB', '16GB', '32GB']),
            'almacenamiento'=> $this->faker->optional(0.6)->randomElement(['256GB SSD', '512GB SSD', '1TB HDD', '1TB SSD']),
            'status'        => $this->faker->randomElement([
                Equipo::STATUS_DISPONIBLE,
                Equipo::STATUS_EN_MANTENIMIENTO,
            ]),
            'observaciones' => $this->faker->optional()->sentence(),
        ];
    }

    public function disponible(): static
    {
        return $this->state(fn () => ['status' => Equipo::STATUS_DISPONIBLE]);
    }
}