<?php

namespace App\Models;

use Database\Factories\JadwalPeriksaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_dokter', 'hari', 'jam_mulai', 'jam_selesai'])]
class JadwalPeriksa extends Model
{
    /** @use HasFactory<JadwalPeriksaFactory> */
    use HasFactory;

    protected $table = 'jadwal_periksa';

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }

    public function daftarPolis(): HasMany
    {
        return $this->hasMany(DaftarPoli::class, 'id_jadwal');
    }
}
