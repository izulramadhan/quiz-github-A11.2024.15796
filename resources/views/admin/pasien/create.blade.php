<x-layout title="Tambah Pasien" subtitle="Masukkan informasi pasien poliklinik.">
    <a href="{{ route('pasien.index') }}" class="mb-5 inline-block text-sm font-medium text-slate-500 hover:text-indigo-700">← Kembali ke daftar pasien</a>
    <section class="panel max-w-2xl"><div class="panel-heading"><span class="step-icon">+</span><h2 class="font-semibold">Informasi pasien</h2></div><form action="{{ route('pasien.store') }}" method="POST" class="flex flex-col gap-5 p-6">@csrf @include('admin.partials.user-form', ['role' => 'pasien'])</form></section>
</x-layout>
