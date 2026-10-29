<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Daftar Pasien — Poliklinik</title><link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased">
    <main class="grid min-h-screen lg:grid-cols-2">
        <section class="hidden flex-col justify-between bg-indigo-950 p-14 text-white lg:flex">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-xl font-bold"><span class="flex size-10 items-center justify-center rounded-xl bg-white text-3xl text-indigo-800">+</span>Poliklinik</a>
            <div><p class="mb-5 text-xs font-semibold uppercase tracking-[0.25em] text-indigo-300">Mulai pelayanan Anda</p><h1 class="max-w-lg text-4xl font-bold leading-tight">Satu akun untuk<br>layanan kesehatan Anda.</h1><p class="mt-6 max-w-md text-sm leading-7 text-indigo-200">Daftar sebagai pasien untuk memilih jadwal poli, mendapatkan nomor antrean, dan melihat riwayat pemeriksaan.</p></div>
            <p class="text-xs text-indigo-300">Poliklinik · Layanan kesehatan</p>
        </section>
        <section class="flex items-center justify-center px-6 py-10">
            <div class="w-full max-w-lg">
                <span class="mb-5 inline-flex rounded-lg bg-indigo-100 px-3 py-2 text-xs font-semibold text-indigo-700">PENDAFTARAN PASIEN</span>
                <h2 class="text-3xl font-bold text-slate-900">Buat akun pasien</h2>
                <p class="mb-6 mt-3 text-sm leading-6 text-slate-500">Lengkapi data Anda. Nomor rekam medis dibuat secara otomatis.</p>
                @if($errors->any())
                    <div role="alert" class="dui-alert dui-alert-error mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form method="POST" action="{{ route('register.store') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @csrf
                    <div class="sm:col-span-2"><label for="nama" class="field-label">Nama lengkap</label><input class="dui-input field w-full" id="nama" name="nama" autocomplete="name" value="{{ old('nama') }}" maxlength="255" placeholder="Nama sesuai KTP" required autofocus></div>
                    <div><label for="no_ktp" class="field-label">NIK (16 digit)</label><input class="dui-input field w-full" id="no_ktp" name="no_ktp" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" value="{{ old('no_ktp') }}" placeholder="16 digit NIK" required></div>
                    <div><label for="no_hp" class="field-label">Nomor HP</label><input class="dui-input field w-full" id="no_hp" name="no_hp" type="tel" autocomplete="tel" pattern="[0-9]{10,15}" maxlength="15" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required></div>
                    <div class="sm:col-span-2"><label for="alamat" class="field-label">Alamat</label><textarea class="dui-textarea field w-full" id="alamat" name="alamat" autocomplete="street-address" rows="2" maxlength="255" placeholder="Alamat tempat tinggal" required>{{ old('alamat') }}</textarea></div>
                    <div class="sm:col-span-2"><label for="email" class="field-label">Email</label><input class="dui-input field w-full" id="email" name="email" type="email" autocomplete="username" maxlength="255" value="{{ old('email') }}" placeholder="nama@email.com" required></div>
                    <div><label for="password" class="field-label">Kata sandi</label><input class="dui-input field w-full" id="password" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="72" placeholder="Minimal 8 karakter" required></div>
                    <div><label for="password_confirmation" class="field-label">Konfirmasi kata sandi</label><input class="dui-input field w-full" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" maxlength="72" placeholder="Ulangi kata sandi" required></div>
                    <button type="submit" class="dui-btn dui-btn-primary btn-primary w-full justify-center py-3 sm:col-span-2">Daftar sebagai pasien <span aria-hidden="true">→</span></button>
                </form>
                <p class="mt-5 text-sm text-slate-500">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-semibold text-indigo-700 hover:underline">Masuk ke Poliklinik</a></p>
            </div>
        </section>
    </main>
</body>
</html>
