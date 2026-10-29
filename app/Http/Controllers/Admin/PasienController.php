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

class PasienController extends Controller
{
    public function index(): View
    {
        return view('admin.pasien.index', ['users' => User::where('role', 'pasien')->with('poli')->withCount('daftarPolis')->orderBy('nama')->paginate(10)]);
    }

    public function create(): View
    {
        return view('admin.pasien.create', ['user' => new User, 'polis' => Poli::orderBy('nama_poli')->get()]);
    }

    public function store(SaveUserRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = $request->validated();
            $user = new User($data);
            $user->role = 'pasien';

            $user->save();
            $user->no_rm = now()->format('Ym').'-'.str_pad((string) $user->id, 3, '0', STR_PAD_LEFT);
            $user->save();
        });

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function edit(User $pasien): View
    {
        abort_unless($pasien->role === 'pasien', 404);

        return view('admin.pasien.edit', ['user' => $pasien, 'polis' => Poli::orderBy('nama_poli')->get()]);
    }

    public function update(SaveUserRequest $request, User $pasien): RedirectResponse
    {
        abort_unless($pasien->role === 'pasien', 404);
        $data = $request->validated();
        if (! $request->filled('password')) {
            unset($data['password']);
        }
        $pasien->fill($data);

        $pasien->save();

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(User $pasien): RedirectResponse
    {
        abort_unless($pasien->role === 'pasien', 404);
        DB::transaction(function () use ($pasien): void {
            $record = User::where('role', 'pasien')->lockForUpdate()->findOrFail($pasien->id);
            if ($record->daftarPolis()->exists()) {
                throw ValidationException::withMessages(['pasien' => 'Pasien sudah memiliki pendaftaran atau riwayat pemeriksaan dan tidak dapat dihapus.']);
            }
            $record->delete();
        });

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
