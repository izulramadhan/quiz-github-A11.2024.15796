<?php

namespace Database\Factories;

use App\Models\Poli;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Poli> */
class PoliFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_poli' => 'Poli '.fake()->unique()->word(),
            'keterangan' => fake()->sentence(),
        ];
    }
}
