<?php

namespace Database\Factories;

use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Periksa> */
class PeriksaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_daftar_poli' => DaftarPoli::factory(),
            'tgl_periksa' => now(),
            'catatan' => fake()->sentence(),
            'biaya_periksa' => 150000,
        ];
    }
}
