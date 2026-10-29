<?php

namespace Database\Factories;

use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<DetailPeriksa> */
class DetailPeriksaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_periksa' => Periksa::factory(),
            'id_obat' => Obat::factory(),
        ];
    }
}
