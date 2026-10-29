<x-layout title="Edit Jadwal Periksa" subtitle="Perbarui waktu pelayanan pemeriksaan pada jadwal Anda.">
    <a href="{{ route('jadwal-periksa.index') }}" class="mb-5 inline-block text-sm font-medium text-slate-500 hover:text-indigo-700">← Kembali ke daftar jadwal</a>
    <section class="panel max-w-2xl"><div class="panel-heading"><span class="step-icon">↗</span><h2 class="font-semibold">Informasi jadwal #{{ $jadwalPeriksa->id }}</h2></div><form action="{{ route('jadwal-periksa.update', $jadwalPeriksa->id) }}" method="POST" class="flex flex-col gap-6 p-6">@csrf @method('PUT') @include('dokter.jadwal-periksa.form')</form></section>
</x-layout>
