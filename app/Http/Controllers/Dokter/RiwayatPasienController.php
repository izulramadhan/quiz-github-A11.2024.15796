<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatPasienController extends Controller
{
    public function index(Request $request): View
    {
        return view('dokter.riwayat-pasien.index', [
            'riwayatPasien' => Periksa::whereHas('daftarPoli.jadwalPeriksa', fn (Builder $query) => $query->where('id_dokter', $request->user()->id))
                ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli', 'detailPeriksas.obat'])
                ->orderByDesc('tgl_periksa')->paginate(10),
        ]);
    }

    public function show(Request $request, int $id): View
    {
        return view('dokter.riwayat-pasien.show', [
            'periksa' => Periksa::whereHas('daftarPoli.jadwalPeriksa', fn (Builder $query) => $query->where('id_dokter', $request->user()->id))
                ->with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli', 'detailPeriksas.obat'])
                ->findOrFail($id),
        ]);
    }
}
