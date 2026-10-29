<?php

namespace App\Models;

use Database\Factories\PeriksaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_daftar_poli', 'tgl_periksa', 'catatan', 'biaya_periksa'])]
class Periksa extends Model
{
    /** @use HasFactory<PeriksaFactory> */
    use HasFactory;

    protected $table = 'periksa';

    public function daftarPoli(): BelongsTo
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    public function detailPeriksas(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }

    protected function casts(): array
    {
        return [
            'tgl_periksa' => 'datetime',
            'biaya_periksa' => 'integer',
        ];
    }
}
