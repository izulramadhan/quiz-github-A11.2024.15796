<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! in_array($request->user()->role, ['admin', 'pasien', 'dokter'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Role akun tidak dikenali.']);
        }

        return redirect()->route(match ($request->user()->role) {
            'admin' => 'obat.index',
            'dokter' => 'periksa-pasien.index',
            default => 'pasien.daftar',
        });
    }

    public function createRegistration(): View
    {
        return view('auth.register');
    }

    public function storeRegistration(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_ktp' => ['required', 'regex:/^[0-9]{16}$/', 'unique:users,no_ktp'],
            'no_hp' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ], [
            'required' => ':attribute wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan masuk.',
            'no_ktp.unique' => 'NIK sudah terdaftar.',
            'no_ktp.regex' => 'NIK harus terdiri dari 16 digit angka.',
            'no_hp.regex' => 'Nomor HP harus terdiri dari 10 sampai 15 digit angka.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ], [
            'nama' => 'Nama lengkap',
            'alamat' => 'Alamat',
            'no_ktp' => 'NIK',
            'no_hp' => 'Nomor HP',
            'password' => 'Kata sandi',
        ]);

        DB::transaction(function () use ($validated): void {
            $pasien = new User($validated);
            $pasien->role = 'pasien';
            $pasien->save();
            $pasien->no_rm = now()->format('Ym').'-'.str_pad((string) $pasien->id, 3, '0', STR_PAD_LEFT);
            $pasien->save();
        });

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk menggunakan akun Anda.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
