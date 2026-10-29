<?php

namespace App\Models;

use Database\Factories\DaftarPoliFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_pasien', 'id_jadwal', 'keluhan', 'no_antrian'])]
class DaftarPoli extends Model
{
    /** @use HasFactory<DaftarPoliFactory> */
    use HasFactory;

    protected $table = 'daftar_poli';

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pasien');
    }

    public function jadwalPeriksa(): BelongsTo
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal');
    }

    public function periksas(): HasMany
    {
        return $this->hasMany(Periksa::class, 'id_daftar_poli');
    }

    protected function casts(): array
    {
        return [
            'no_antrian' => 'integer',
        ];
    }
}
