<?php

namespace App\Models;

use Database\Factories\ObatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_obat', 'kemasan', 'harga'])]
class Obat extends Model
{
    /** @use HasFactory<ObatFactory> */
    use HasFactory;

    protected $table = 'obat';

    public function detailPeriksas(): HasMany
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
        ];
    }
}
