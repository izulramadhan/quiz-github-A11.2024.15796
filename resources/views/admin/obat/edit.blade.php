<x-layout title="Edit Obat" subtitle="Perbarui informasi obat yang tersedia dalam katalog.">
    <a href="{{ route('obat.index') }}" class="mb-5 inline-block text-sm font-medium text-slate-500 hover:text-indigo-700">← Kembali ke daftar obat</a>
    <section class="panel max-w-2xl"><div class="panel-heading"><span class="step-icon">↗</span><h2 class="font-semibold">Informasi obat #{{ $obat->id }}</h2></div><form action="{{ route('obat.update', $obat) }}" method="POST" class="flex flex-col gap-6 p-6">@csrf @method('PUT') @include('admin.obat.form')</form></section>
</x-layout>
