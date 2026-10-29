<x-layout title="Daftar Poli" subtitle="Kelola layanan dan informasi poliklinik yang tersedia.">
    <section class="panel">
        <div class="panel-heading justify-between"><div><h2 class="font-semibold">Manajemen Poli</h2><p class="mt-1 text-xs text-slate-500">{{ $polis->total() }} poli terdaftar.</p></div><a href="{{ route('polis.create') }}" class="btn-primary">+ Tambah Poli</a></div>
        <div class="overflow-x-auto"><table class="w-full text-left text-sm">
            <thead><tr><th class="table-head">ID</th><th class="table-head">Nama poli</th><th class="table-head">Keterangan</th><th class="table-head">Dokter</th><th class="table-head text-right">Aksi</th></tr></thead>
            <tbody>@forelse($polis as $poli)<tr>
                <td class="table-cell text-slate-400">#{{ $poli->id }}</td><td class="table-cell font-semibold">{{ $poli->nama_poli }}</td><td class="table-cell max-w-md text-slate-500">{{ $poli->keterangan ?: '—' }}</td><td class="table-cell">{{ $poli->dokters_count }} dokter</td>
                <td class="table-cell"><div class="flex justify-end gap-2"><a href="{{ route('polis.edit', $poli) }}" class="btn-secondary">Edit</a><form method="POST" action="{{ route('polis.destroy', $poli) }}" data-confirm="Hapus poli ini?">@csrf @method('DELETE')<button type="submit" @disabled($poli->dokters_count > 0) title="{{ $poli->dokters_count > 0 ? 'Poli masih terhubung dengan dokter.' : 'Hapus poli' }}" class="btn-secondary border-red-100 text-red-600 disabled:cursor-not-allowed disabled:opacity-40">Hapus</button></form></div></td>
            </tr>@empty<tr><td colspan="5" class="p-12 text-center text-slate-400">Belum ada data poli. Klik Tambah Poli untuk memulai.</td></tr>@endforelse</tbody>
        </table></div>
        @if($polis->hasPages())<div class="border-t border-slate-100 p-5">{{ $polis->links() }}</div>@endif
    </section>
    <p class="mt-4 text-xs text-slate-400">Poli yang masih memiliki dokter perlu dikosongkan terlebih dahulu sebelum dihapus.</p>
</x-layout>
