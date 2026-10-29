<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveUserRequest;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DokterController extends Controller
{
    public function index(): View
    {
        return view('admin.dokter.index', ['users' => User::where('role', 'dokter')->with('poli')->withCount('jadwalPeriksas')->orderBy('nama')->paginate(10)]);
    }

    public function create(): View
    {
        return view('admin.dokter.create', ['user' => new User, 'polis' => Poli::orderBy('nama_poli')->get()]);
    }

    public function store(SaveUserRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = $request->validated();
            $poliId = $data['id_poli'];
            unset($data['id_poli']);
            $user = new User($data);
            $user->role = 'dokter';
            $user->id_poli = $poliId;
            $user->save();
        });

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function edit(User $dokter): View
    {
        abort_unless($dokter->role === 'dokter', 404);

        return view('admin.dokter.edit', ['user' => $dokter, 'polis' => Poli::orderBy('nama_poli')->get()]);
    }

    public function update(SaveUserRequest $request, User $dokter): RedirectResponse
    {
        abort_unless($dokter->role === 'dokter', 404);
        $data = $request->validated();
        if (! $request->filled('password')) {
            unset($data['password']);
        }
        $dokter->id_poli = $data['id_poli'];
        unset($data['id_poli']);
        $dokter->fill($data);
        $dokter->save();

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(User $dokter): RedirectResponse
    {
        abort_unless($dokter->role === 'dokter', 404);
        DB::transaction(function () use ($dokter): void {
            $record = User::where('role', 'dokter')->lockForUpdate()->findOrFail($dokter->id);
            if ($record->jadwalPeriksas()->exists()) {
                throw ValidationException::withMessages(['dokter' => 'Dokter masih memiliki jadwal periksa. Hapus jadwal yang belum digunakan terlebih dahulu.']);
            }
            $record->delete();
        });

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil dihapus.');
    }
}
