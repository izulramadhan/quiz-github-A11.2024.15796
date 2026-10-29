<x-layout title="Edit Poli" subtitle="Perbarui informasi poli poliklinik.">
    <a href="{{ route('polis.index') }}" class="mb-5 inline-block text-sm font-medium text-slate-500 hover:text-indigo-700">← Kembali ke daftar poli</a>
    <section class="panel max-w-2xl"><div class="panel-heading"><span class="step-icon">↗</span><h2 class="font-semibold">Informasi poli</h2></div><form action="{{ route('polis.update', $poli) }}" method="POST" class="flex flex-col gap-5 p-6">@csrf @method('PUT') @include('admin.polis.form')</form></section>
</x-layout>
