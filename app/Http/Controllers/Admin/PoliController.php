<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PoliController extends Controller
{
    public function index(): View
    {
        return view('admin.polis.index', ['polis' => Poli::withCount('dokters')->orderBy('nama_poli')->paginate(10)]);
    }

    public function create(): View
    {
        return view('admin.polis.create', ['poli' => new Poli]);
    }

    public function store(Request $request): RedirectResponse
    {
        Poli::create($this->validatedData($request));

        return redirect()->route('polis.index')->with('success', 'Data poli berhasil ditambahkan.');
    }

    public function edit(Poli $poli): View
    {
        return view('admin.polis.edit', compact('poli'));
    }

    public function update(Request $request, Poli $poli): RedirectResponse
    {
        $poli->update($this->validatedData($request));

        return redirect()->route('polis.index')->with('success', 'Data poli berhasil diperbarui.');
    }

    public function destroy(Poli $poli): RedirectResponse
    {
        DB::transaction(function () use ($poli): void {
            $record = Poli::lockForUpdate()->findOrFail($poli->id);
            if ($record->dokters()->exists()) {
                throw ValidationException::withMessages(['poli' => 'Poli masih terhubung dengan dokter dan tidak dapat dihapus.']);
            }
            $record->delete();
        });

        return redirect()->route('polis.index')->with('success', 'Data poli berhasil dihapus.');
    }

    /** @return array{nama_poli: string, keterangan?: ?string} */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'nama_poli' => ['required', 'string', 'max:25'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_poli.required' => 'Nama poli wajib diisi.',
            'nama_poli.max' => 'Nama poli maksimal 25 karakter.',
            'max' => ':attribute maksimal 255 karakter.',
        ], ['nama_poli' => 'Nama poli', 'keterangan' => 'Keterangan']);
    }
}
