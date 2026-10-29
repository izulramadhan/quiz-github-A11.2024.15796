<x-layout title="Tambah Obat" subtitle="Masukkan data obat baru ke dalam katalog poliklinik.">
    <a href="{{ route('obat.index') }}" class="mb-5 inline-block text-sm font-medium text-slate-500 hover:text-indigo-700">← Kembali ke daftar obat</a>
    <section class="panel max-w-2xl"><div class="panel-heading"><span class="step-icon">+</span><h2 class="font-semibold">Informasi obat</h2></div><form action="{{ route('obat.store') }}" method="POST" class="flex flex-col gap-6 p-6">@csrf @include('admin.obat.form')</form></section>
</x-layout>
