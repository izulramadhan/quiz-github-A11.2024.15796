<?php

namespace App\Models;

use Database\Factories\DetailPeriksaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_periksa', 'id_obat'])]
class DetailPeriksa extends Model
{
    /** @use HasFactory<DetailPeriksaFactory> */
    use HasFactory;

    protected $table = 'detail_periksa';

    public function periksa(): BelongsTo
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
