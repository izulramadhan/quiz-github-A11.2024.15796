<?php

namespace Database\Factories;

use App\Models\Obat;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Obat> */
class ObatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_obat' => fake()->word().' 500 mg',
            'kemasan' => 'Strip',
            'harga' => fake()->numberBetween(1000, 25000),
        ];
    }
}
