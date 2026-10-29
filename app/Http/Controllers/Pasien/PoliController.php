<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PoliController extends Controller
{
    public function get(Request $request): View
    {
        return view('pasien.daftar', [
            'user' => $request->user(),
            'polis' => Poli::orderBy('nama_poli')->get(),
            'jadwals' => JadwalPeriksa::with('dokter.poli')
                ->whereHas('dokter', fn (Builder $query) => $query->where('role', 'dokter'))
                ->orderBy('hari')->orderBy('jam_mulai')->get(),
            'pendaftarans' => $request->user()->daftarPolis()
                ->with(['jadwalPeriksa.dokter.poli', 'periksas'])->latest()->paginate(5),
        ]);
    }

    public function submit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_poli' => ['required', 'integer', 'exists:poli,id'],
            'id_jadwal' => ['required', 'integer', 'exists:jadwal_periksa,id'],
            'keluhan' => ['required', 'string', 'max:2000'],
        ], [
            'keluhan.required' => 'Tuliskan keluhan yang ingin diperiksakan.',
        ]);

        $pendaftaran = DB::transaction(function () use ($request, $data): DaftarPoli {
            $jadwal = JadwalPeriksa::whereHas('dokter', fn (Builder $query) => $query
                ->where('role', 'dokter')->where('id_poli', $data['id_poli']))
                ->lockForUpdate()->find($data['id_jadwal']);

            if (! $jadwal) {
                throw ValidationException::withMessages(['id_jadwal' => 'Jadwal dokter tidak sesuai dengan poli yang dipilih.']);
            }

            if (DaftarPoli::where('id_pasien', $request->user()->id)->where('id_jadwal', $jadwal->id)
                ->whereDoesntHave('periksas')->exists()) {
                throw ValidationException::withMessages(['id_jadwal' => 'Anda masih memiliki pendaftaran yang menunggu pemeriksaan pada jadwal ini.']);
            }

            return DaftarPoli::create([
                'id_pasien' => $request->user()->id,
                'id_jadwal' => $jadwal->id,
                'keluhan' => $data['keluhan'],
                'no_antrian' => (int) DaftarPoli::where('id_jadwal', $jadwal->id)->max('no_antrian') + 1,
            ]);
        });

        return redirect()->route('pasien.daftar')->with('success', 'Berhasil mendaftar ke poli. Nomor antrean Anda: '.$pendaftaran->no_antrian.'.');
    }
}
