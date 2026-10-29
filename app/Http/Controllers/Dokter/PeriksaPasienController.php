<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PeriksaPasienController extends Controller
{
    private const BIAYA_JASA = 150000;

    public function index(Request $request): View
    {
        $query = DaftarPoli::whereHas('jadwalPeriksa', fn (Builder $query) => $query->where('id_dokter', $request->user()->id));

        return view('dokter.periksa-pasien.index', [
            'daftarPasien' => (clone $query)->with(['pasien', 'jadwalPeriksa', 'periksas'])
                ->orderBy('no_antrian')->orderBy('id')->paginate(10),
            'totalPasien' => (clone $query)->count(),
            'menunggu' => (clone $query)->whereDoesntHave('periksas')->count(),
            'selesai' => (clone $query)->whereHas('periksas')->count(),
        ]);
    }

    public function create(Request $request, int $id): View|RedirectResponse
    {
        $pendaftaran = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli', 'periksas'])
            ->whereHas('jadwalPeriksa', fn (Builder $query) => $query->where('id_dokter', $request->user()->id))
            ->findOrFail($id);

        if ($pendaftaran->periksas->isNotEmpty()) {
            return redirect()->route('riwayat-pasien.show', $pendaftaran->periksas->first()->id);
        }

        return view('dokter.periksa-pasien.create', [
            'pendaftaran' => $pendaftaran,
            'obats' => Obat::orderBy('nama_obat')->get(),
            'biayaJasa' => self::BIAYA_JASA,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_daftar_poli' => ['required', 'integer'],
            'obat_json' => ['required', 'json'],
            'catatan' => ['nullable', 'string', 'max:5000'],
        ]);
        $pilihan = Validator::make(['obat_ids' => json_decode($data['obat_json'], true)], [
            'obat_ids' => ['required', 'array', 'min:1'],
            'obat_ids.*' => ['required', 'integer', 'distinct', 'exists:obat,id'],
        ], ['obat_ids.required' => 'Pilih sedikitnya satu obat.'])->validate();

        DB::transaction(function () use ($request, $data, $pilihan): void {
            $pendaftaran = DaftarPoli::whereHas('jadwalPeriksa', fn (Builder $query) => $query->where('id_dokter', $request->user()->id))
                ->lockForUpdate()->findOrFail($data['id_daftar_poli']);

            if ($pendaftaran->periksas()->exists()) {
                throw ValidationException::withMessages(['id_daftar_poli' => 'Pasien ini sudah diperiksa.']);
            }

            $obats = Obat::whereIn('id', $pilihan['obat_ids'])->get();
            $periksa = Periksa::create([
                'id_daftar_poli' => $pendaftaran->id,
                'tgl_periksa' => now(),
                'catatan' => $data['catatan'] ?? null,
                'biaya_periksa' => self::BIAYA_JASA + (int) $obats->sum('harga'),
            ]);
            $periksa->detailPeriksas()->createMany($obats->map(fn (Obat $obat): array => ['id_obat' => $obat->id])->all());
        });

        return redirect()->route('periksa-pasien.index')->with('success', 'Hasil pemeriksaan dan resep obat berhasil disimpan.');
    }
}
