<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ObatController extends Controller
{
    public function index(): View
    {
        return view('admin.obat.index', ['obats' => Obat::withCount('detailPeriksas')->orderBy('nama_obat')->paginate(10)]);
    }

    public function create(): View
    {
        return view('admin.obat.create', ['obat' => new Obat]);
    }

    public function store(Request $request): RedirectResponse
    {
        Obat::create($this->validatedData($request));

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function edit(Obat $obat): View
    {
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat): RedirectResponse
    {
        $obat->update($this->validatedData($request));

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Obat $obat): RedirectResponse
    {
        DB::transaction(function () use ($obat): void {
            $record = Obat::lockForUpdate()->findOrFail($obat->id);
            if ($record->detailPeriksas()->exists()) {
                throw ValidationException::withMessages(['obat' => 'Obat sudah digunakan pada riwayat pemeriksaan dan tidak dapat dihapus.']);
            }
            $record->delete();
        });

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil dihapus.');
    }

    /** @return array{nama_obat: string, kemasan?: ?string, harga: int|string} */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kemasan' => ['nullable', 'string', 'max:35'],
            'harga' => ['required', 'integer', 'min:0', 'max:2147483647'],
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'kemasan.max' => 'Kemasan maksimal 35 karakter.',
            'harga.min' => 'Harga obat tidak boleh negatif.',
        ]);
    }
}
