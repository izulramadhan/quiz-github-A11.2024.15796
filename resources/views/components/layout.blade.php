@props(['title', 'subtitle' => ''])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — Poliklinik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    @include('components.partials.sidebar')
    <div class="flex min-h-screen flex-col md:pl-64">
        <header class="flex min-h-20 items-center justify-between gap-4 border-b border-slate-200 bg-white px-6 lg:px-10">
            <div class="flex items-center gap-3 text-sm"><span class="font-semibold text-indigo-700">Poliklinik</span><span class="text-slate-300">/</span><span class="text-slate-500">{{ $title }}</span></div>
            <div class="flex items-center gap-3"><div class="hidden text-right sm:block"><p class="text-sm font-semibold">{{ auth()->user()->nama }}</p><p class="mt-0.5 text-xs text-slate-500">{{ ucfirst(auth()->user()->role) }}</p></div><span class="flex size-10 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">{{ mb_strtoupper(mb_substr(auth()->user()->nama, 0, 1)) }}</span></div>
        </header>
        <nav class="flex gap-2 overflow-x-auto border-b border-slate-200 bg-white p-3 text-sm md:hidden" aria-label="Navigasi seluler">
            @if(auth()->user()->role === 'pasien')
                <a href="{{ route('pasien.daftar') }}" class="btn-secondary">Daftar Poli</a>
            @elseif(auth()->user()->role === 'admin')
                <a href="{{ route('polis.index') }}" class="btn-secondary">Manajemen Poli</a>
                <a href="{{ route('dokter.index') }}" class="btn-secondary">Manajemen Dokter</a>
                <a href="{{ route('pasien.index') }}" class="btn-secondary">Manajemen Pasien</a>
                <a href="{{ route('obat.index') }}" class="btn-secondary">Manajemen Obat</a>
            @else
                <a href="{{ route('jadwal-periksa.index') }}" class="btn-secondary">Jadwal Periksa</a>
                <a href="{{ route('periksa-pasien.index') }}" class="btn-secondary">Periksa Pasien</a>
                <a href="{{ route('riwayat-pasien.index') }}" class="btn-secondary">Riwayat Pasien</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-secondary" type="submit">Keluar</button></form>
        </nav>
        <main class="w-full flex-1 px-5 py-8 sm:px-6 lg:px-10">
            <div class="mb-7 flex flex-wrap items-start justify-between gap-3"><div><h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>@if($subtitle)<p class="mt-2 text-sm text-slate-500">{{ $subtitle }}</p>@endif</div><span class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-500">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span></div>
            @if(session('success'))<div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
            @if($errors->any())<div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700"><p class="mb-2 font-semibold">Periksa kembali data Anda.</p><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            {{ $slot }}
        </main>
        <footer class="border-t border-slate-200 px-6 py-5 text-xs text-slate-400 lg:px-10">© {{ now()->year }} Poliklinik <span class="mx-2">·</span> Pelayanan kesehatan yang terhubung</footer>
    </div>
</body>
</html>
