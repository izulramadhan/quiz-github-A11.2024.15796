# quiz-github-A11.2024.15796
Nama         : Azizul Purnama RAmadhan
NIM          : A11.2024.15796 
Kelas        : DEV-05
Mata Kuliah  : Bengkel Koding

## Bukti Implementasi Daftar Poli, Periksa Pasien, dan Riwayat Pasien

| Fitur | Role | URL lokal | Screenshot |
| --- | --- | --- | --- |
| Daftar Poli | Pasien | `/pasien/daftar` | [Lihat screenshot](storage/app/private/tugas-praktek-5120/01-daftar-poli-pasien.png) |
| Periksa Pasien | Dokter | `/dokter/periksa-pasien` | [Lihat screenshot](storage/app/private/tugas-praktek-5120/02-periksa-pasien-dokter.png) |
| Riwayat Pasien | Dokter | `/dokter/riwayat-pasien` | [Lihat screenshot](storage/app/private/tugas-praktek-5120/03-riwayat-pasien-dokter.png) |

[Unduh PDF pengumpulan (3 halaman)](storage/app/private/tugas-praktek-5120/Tugas-Praktek-Poliklinik-A11.2024.15796.pdf)

Implementasi controller:

- [Daftar Poli](app/Http/Controllers/Pasien/PoliController.php)
- [Periksa Pasien](app/Http/Controllers/Dokter/PeriksaPasienController.php)
- [Riwayat Pasien](app/Http/Controllers/Dokter/RiwayatPasienController.php)
- [Routing dan pembatasan role](routes/web.php)
