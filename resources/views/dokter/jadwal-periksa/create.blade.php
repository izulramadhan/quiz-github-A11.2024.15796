<x-layout title="Tambah Jadwal Periksa" subtitle="Tentukan hari dan waktu pemeriksaan untuk pasien Anda.">
    <a href="{{ route('jadwal-periksa.index') }}" class="mb-5 inline-block text-sm font-medium text-slate-500 hover:text-indigo-700">← Kembali ke daftar jadwal</a>
    <section class="panel max-w-2xl"><div class="panel-heading"><span class="step-icon">+</span><h2 class="font-semibold">Informasi jadwal periksa</h2></div><form action="{{ route('jadwal-periksa.store') }}" method="POST" class="flex flex-col gap-6 p-6">@csrf @include('dokter.jadwal-periksa.form')</form></section>
</x-layout>
