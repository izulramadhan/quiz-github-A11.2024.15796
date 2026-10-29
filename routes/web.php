<?php

use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PoliController as AdminPoliController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\PoliController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::middleware('guest')->group(function (): void {
    Route::get('/register', [AuthController::class, 'createRegistration'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegistration'])->middleware('throttle:6,1')->name('register.store');
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
});
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('/dashboard', fn (Request $request) => redirect()->route(match ($request->user()->role) {
    'admin' => 'obat.index',
    'dokter' => 'periksa-pasien.index',
    default => 'pasien.daftar',
}))->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function (): void {
    Route::redirect('/dashboard', '/admin/obat')->name('admin.dashboard');
    Route::resource('polis', AdminPoliController::class)->except('show')->parameters(['polis' => 'poli']);
    Route::resource('dokter', DokterController::class)->except('show');
    Route::resource('obat', ObatController::class)->except('show');
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function (): void {
    Route::redirect('/dashboard', '/pasien/daftar')->name('pasien.dashboard');
    Route::get('/daftar', [PoliController::class, 'get'])->name('pasien.daftar');
    Route::post('/daftar', [PoliController::class, 'submit'])->name('pasien.daftar.submit');
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function (): void {
    Route::redirect('/dashboard', '/dokter/periksa-pasien')->name('dokter.dashboard');
    Route::resource('jadwal-periksa', JadwalPeriksaController::class)->except('show')
        ->parameters(['jadwal-periksa' => 'jadwal_periksa']);
    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');
    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])->whereNumber('id')->name('periksa-pasien.create');
    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->whereNumber('id')->name('riwayat-pasien.show');
});
