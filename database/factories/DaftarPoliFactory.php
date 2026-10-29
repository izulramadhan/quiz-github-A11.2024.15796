<?php

namespace Database\Factories;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<DaftarPoli> */
class DaftarPoliFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_pasien' => User::factory(),
            'id_jadwal' => JadwalPeriksa::factory(),
            'keluhan' => fake()->sentence(),
            'no_antrian' => 1,
        ];
    }
}
