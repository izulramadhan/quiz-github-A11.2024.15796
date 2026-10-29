<?php

namespace Database\Seeders;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliklinikDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->demoUser('admin@poliklinik.test', ['nama' => 'Admin Poliklinik', 'role' => 'admin']);
            $umum = Poli::firstOrCreate(['nama_poli' => 'Poli Umum'], ['keterangan' => 'Pelayanan pemeriksaan dan konsultasi kesehatan umum.']);
            $gigi = Poli::firstOrCreate(['nama_poli' => 'Poli Gigi'], ['keterangan' => 'Pelayanan kesehatan gigi dan mulut.']);
            $dokter = $this->demoUser('dokter@poliklinik.test', ['nama' => 'dr. Andi Pratama', 'role' => 'dokter', 'id_poli' => $umum->id]);
            $dokterGigi = $this->demoUser('gigi@poliklinik.test', ['nama' => 'drg. Maya Lestari', 'role' => 'dokter', 'id_poli' => $gigi->id]);
            $pasien = $this->demoUser('pasien@poliklinik.test', ['nama' => 'Nadia Putri', 'role' => 'pasien', 'no_rm' => 'RM-DEMO-001', 'no_hp' => '081234560001', 'alamat' => 'Semarang']);
            $pasienKedua = $this->demoUser('budi@poliklinik.test', ['nama' => 'Budi Santoso', 'role' => 'pasien', 'no_rm' => 'RM-DEMO-002', 'no_hp' => '081234560002']);
            $jadwal = JadwalPeriksa::firstOrCreate(['id_dokter' => $dokter->id, 'hari' => 'Senin', 'jam_mulai' => '08:00:00'], ['jam_selesai' => '12:00:00']);
            JadwalPeriksa::firstOrCreate(['id_dokter' => $dokter->id, 'hari' => 'Kamis', 'jam_mulai' => '09:00:00'], ['jam_selesai' => '13:00:00']);
            JadwalPeriksa::firstOrCreate(['id_dokter' => $dokterGigi->id, 'hari' => 'Selasa', 'jam_mulai' => '08:00:00'], ['jam_selesai' => '11:00:00']);
            $paracetamol = Obat::firstOrCreate(['nama_obat' => 'Paracetamol 500 mg'], ['kemasan' => 'Strip 10 tablet', 'harga' => 8000]);
            Obat::firstOrCreate(['nama_obat' => 'Vitamin C 500 mg'], ['kemasan' => 'Strip 10 tablet', 'harga' => 12000]);
            Obat::firstOrCreate(['nama_obat' => 'Antasida DOEN'], ['kemasan' => 'Strip 10 tablet', 'harga' => 6000]);
            $selesai = $this->demoPendaftaran($pasien, $jadwal, 'Demam dan sakit kepala sejak kemarin.');
            $periksa = Periksa::firstOrCreate(['id_daftar_poli' => $selesai->id], [
                'tgl_periksa' => now()->subDay(),
                'catatan' => 'Data contoh untuk latihan: istirahat cukup dan jaga asupan cairan.',
                'biaya_periksa' => 150000 + $paracetamol->harga,
            ]);
            $periksa->detailPeriksas()->firstOrCreate(['id_obat' => $paracetamol->id]);
            $this->demoPendaftaran($pasienKedua, $jadwal, 'Batuk dan tenggorokan terasa tidak nyaman selama dua hari.');
        });
    }

    /** @param array<string, mixed> $attributes */
    private function demoUser(string $email, array $attributes): User
    {
        $user = User::firstOrNew(['email' => $email]);
        if (! $user->exists) {
            $user->forceFill($attributes + ['password' => 'Poliklinik123!', 'email_verified_at' => now()])->save();
        }

        return $user;
    }

    private function demoPendaftaran(User $pasien, JadwalPeriksa $jadwal, string $keluhan): DaftarPoli
    {
        return DaftarPoli::firstOrCreate([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal->id,
            'keluhan' => $keluhan,
        ], ['no_antrian' => (int) DaftarPoli::where('id_jadwal', $jadwal->id)->max('no_antrian') + 1]);
    }
}
