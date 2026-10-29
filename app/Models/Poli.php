<?php

namespace App\Models;

use Database\Factories\PoliFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_poli', 'keterangan'])]
class Poli extends Model
{
    /** @use HasFactory<PoliFactory> */
    use HasFactory;

    protected $table = 'poli';

    public function dokters(): HasMany
    {
        return $this->hasMany(User::class, 'id_poli');
    }
}
