<aside class="fixed inset-y-0 left-0 z-20 hidden w-64 flex-col bg-indigo-950 text-white md:flex">
    <a href="{{ route('dashboard') }}" class="flex h-20 items-center gap-3 border-b border-white/10 px-7"><span class="flex size-9 items-center justify-center rounded-xl bg-white text-2xl font-bold text-indigo-800" aria-hidden="true">+</span><div><span class="block text-lg font-bold tracking-tight">Poliklinik</span><span class="text-[10px] font-medium uppercase tracking-[0.2em] text-indigo-300">Layanan kesehatan</span></div></a>
    <div class="px-5 pt-8"><p class="mb-4 px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-indigo-300">Menu {{ auth()->user()->role }}</p><nav class="flex flex-col gap-2" aria-label="Menu utama">
        @if(auth()->user()->role === 'pasien')
            <a href="{{ route('pasien.daftar') }}" @class(['sidebar-link', 'sidebar-active' => request()->routeIs('pasien.daftar')])><span aria-hidden="true" class="text-lg">⊕</span> Pendaftaran Periksa</a>
        @elseif(auth()->user()->role === 'admin')
            <a href="{{ route('polis.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->routeIs('polis.*')])><span aria-hidden="true" class="text-lg">⊕</span> Manajemen Poli</a>
            <a href="{{ route('dokter.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->routeIs('dokter.*')])><span aria-hidden="true" class="text-lg">✚</span> Manajemen Dokter</a>
            <a href="{{ route('obat.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->routeIs('obat.*')])><span aria-hidden="true" class="text-lg">⊞</span> Manajemen Obat</a>
        @else
            <a href="{{ route('jadwal-periksa.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->routeIs('jadwal-periksa.*')])><span aria-hidden="true" class="text-lg">▦</span> Jadwal Periksa</a>
            <a href="{{ route('periksa-pasien.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->routeIs('periksa-pasien.*')])><span aria-hidden="true" class="text-lg">✚</span> Periksa Pasien</a>
            <a href="{{ route('riwayat-pasien.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->routeIs('riwayat-pasien.*')])><span aria-hidden="true" class="text-lg">↺</span> Riwayat Pasien</a>
        @endif
    </nav></div>
    <div class="mt-auto border-t border-white/10 p-5"><p class="mb-4 px-3 text-xs leading-5 text-indigo-300">{{ match(auth()->user()->role) { 'admin' => 'Kelola poli, dokter, pasien, dan obat dalam satu tempat.', 'dokter' => 'Kelola jadwal, pemeriksaan, dan riwayat pasien dalam satu tempat.', default => 'Daftarkan pemeriksaan dan pantau antrean poli Anda.' } }}</p><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-3 rounded-lg bg-white/10 px-4 py-3 text-left text-sm font-medium text-indigo-100 transition hover:bg-white/20"><span aria-hidden="true">↪</span> Keluar</button></form></div>
</aside>
