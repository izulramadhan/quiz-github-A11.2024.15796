<x-layout title="Edit Dokter" subtitle="Perbarui informasi dokter poliklinik.">
    <a href="{{ route('dokter.index') }}" class="mb-5 inline-block text-sm font-medium text-slate-500 hover:text-indigo-700">← Kembali ke daftar dokter</a>
    <section class="panel max-w-2xl"><div class="panel-heading"><span class="step-icon">↗</span><h2 class="font-semibold">Informasi dokter</h2></div><form action="{{ route('dokter.update', $user) }}" method="POST" class="flex flex-col gap-5 p-6">@csrf @method('PUT') @include('admin.partials.user-form', ['role' => 'dokter'])</form></section>
</x-layout>
