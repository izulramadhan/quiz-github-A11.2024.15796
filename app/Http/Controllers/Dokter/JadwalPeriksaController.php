<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class JadwalPeriksaController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    public function index(Request $request): View
    {
        return view('dokter.jadwal-periksa.index', [
            'jadwalPeriksas' => $request->user()->jadwalPeriksas()->withCount('daftarPolis')
                ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
                ->orderBy('jam_mulai')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('dokter.jadwal-periksa.create', ['jadwalPeriksa' => new JadwalPeriksa, 'haris' => self::HARI]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->saveSchedule($request, $data);

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil ditambahkan.');
    }

    public function edit(Request $request, int $jadwal_periksa): View
    {
        return view('dokter.jadwal-periksa.edit', [
            'jadwalPeriksa' => $request->user()->jadwalPeriksas()->findOrFail($jadwal_periksa),
            'haris' => self::HARI,
        ]);
    }

    public function update(Request $request, int $jadwal_periksa): RedirectResponse
    {
        $record = $request->user()->jadwalPeriksas()->findOrFail($jadwal_periksa);
        $data = $this->validatedData($request);
        $this->saveSchedule($request, $data, $record);

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil diperbarui.');
    }

    public function destroy(Request $request, int $jadwal_periksa): RedirectResponse
    {
        DB::transaction(function () use ($request, $jadwal_periksa): void {
            $record = $request->user()->jadwalPeriksas()->lockForUpdate()->findOrFail($jadwal_periksa);
            if ($record->daftarPolis()->exists()) {
                throw ValidationException::withMessages(['jadwal' => 'Jadwal sudah memiliki pendaftaran pasien dan tidak dapat dihapus.']);
            }
            $record->delete();
        });

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil dihapus.');
    }

    /** @return array{hari: string, jam_mulai: string, jam_selesai: string} */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'hari' => ['required', Rule::in(self::HARI)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ], [
            'hari.in' => 'Pilih hari periksa yang tersedia.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'jam_mulai.date_format' => 'Gunakan format jam HH:MM.',
            'jam_selesai.date_format' => 'Gunakan format jam HH:MM.',
        ]);
    }

    /** @param array{hari: string, jam_mulai: string, jam_selesai: string} $data */
    private function saveSchedule(Request $request, array $data, ?JadwalPeriksa $record = null): void
    {
        DB::transaction(function () use ($request, $data, $record): void {
            $request->user()->newQuery()->whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $overlap = $request->user()->jadwalPeriksas()->where('hari', $data['hari'])
                ->where('jam_mulai', '<', $data['jam_selesai'].':00')
                ->where('jam_selesai', '>', $data['jam_mulai'].':00');
            if ($record) {
                $overlap->whereKeyNot($record->id);
            }
            if ($overlap->exists()) {
                throw ValidationException::withMessages(['jam_mulai' => 'Waktu periksa bertabrakan dengan jadwal Anda yang lain.']);
            }
            if ($record) {
                $record->update($data);
            } else {
                $request->user()->jadwalPeriksas()->create($data);
            }
        });
    }
}
