<div><label for="nama" class="field-label">Nama lengkap <span class="text-red-500">*</span></label><input id="nama" name="nama" class="field" autocomplete="name" maxlength="255" value="{{ old('nama', $user->nama) }}" required autofocus></div>
<div class="grid gap-5 sm:grid-cols-2">
    <div><label for="no_ktp" class="field-label">NIK (16 digit) <span class="text-red-500">*</span></label><input id="no_ktp" name="no_ktp" class="field" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" value="{{ old('no_ktp', $user->no_ktp) }}" required></div>
    <div><label for="no_hp" class="field-label">Nomor HP <span class="text-red-500">*</span></label><input id="no_hp" name="no_hp" type="tel" class="field" pattern="[0-9]{10,15}" maxlength="15" value="{{ old('no_hp', $user->no_hp) }}" required></div>
</div>
<div><label for="alamat" class="field-label">Alamat <span class="text-red-500">*</span></label><textarea id="alamat" name="alamat" class="field" maxlength="255" rows="2" required>{{ old('alamat', $user->alamat) }}</textarea></div>
@if($role === 'dokter')
    <div><label for="id_poli" class="field-label">Poli <span class="text-red-500">*</span></label><select id="id_poli" name="id_poli" class="field" required><option value="">Pilih poli</option>@foreach($polis as $poli)<option value="{{ $poli->id }}" @selected((string) old('id_poli', $user->id_poli) === (string) $poli->id)>{{ $poli->nama_poli }}</option>@endforeach</select>@if($polis->isEmpty())<p class="mt-2 text-xs text-amber-700">Belum ada poli. <a href="{{ route('polis.create') }}" class="underline">Tambahkan poli terlebih dahulu.</a></p>@endif</div>
@elseif($user->exists)
    <div><span class="field-label">Nomor rekam medis</span><p class="rounded-lg bg-indigo-50 px-4 py-3 text-sm font-semibold text-indigo-700">{{ $user->no_rm ?: '—' }}</p></div>
@else
    <p class="text-xs text-slate-500">Nomor rekam medis akan dibuat secara otomatis.</p>
@endif
<div><label for="email" class="field-label">Email <span class="text-red-500">*</span></label><input id="email" name="email" type="email" class="field" maxlength="255" value="{{ old('email', $user->email) }}" autocomplete="off" required></div>
<div class="grid gap-5 sm:grid-cols-2">
    <div><label for="password" class="field-label">Kata sandi{{ $user->exists ? ' baru (opsional)' : '' }}</label><input id="password" name="password" type="password" class="field" autocomplete="new-password" minlength="8" maxlength="72" placeholder="Minimal 8 karakter" @required(! $user->exists)></div>
    <div><label for="password_confirmation" class="field-label">Konfirmasi kata sandi</label><input id="password_confirmation" name="password_confirmation" type="password" class="field" autocomplete="new-password" minlength="8" maxlength="72" placeholder="Ulangi kata sandi" @required(! $user->exists)></div>
</div>
@if($user->exists)<p class="text-xs text-slate-500">Kosongkan kata sandi untuk mempertahankan kata sandi saat ini.</p>@endif
<div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><a href="{{ route($role.'.index') }}" class="btn-secondary">Batal</a><button type="submit" class="btn-primary">{{ $user->exists ? 'Simpan Perubahan' : 'Simpan '.ucfirst($role) }}</button></div>
