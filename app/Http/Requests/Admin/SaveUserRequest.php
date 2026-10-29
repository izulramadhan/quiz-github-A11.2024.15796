<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->user()?->role !== 'admin') {
            return false;
        }

        $record = $this->route('dokter') ?? $this->route('pasien');
        $role = $this->routeIs('dokter.*') ? 'dokter' : 'pasien';
        abort_if($record instanceof User && $record->role !== $role, 404);

        return true;
    }

    /** @return array<string, list<mixed>> */
    public function rules(): array
    {
        $record = $this->route('dokter') ?? $this->route('pasien');
        $user = $record instanceof User ? $record : null;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_ktp' => ['required', 'string', 'regex:/^[0-9]{16}$/', Rule::unique('users', 'no_ktp')->ignore($user)],
            'no_hp' => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'max:72', 'confirmed'],
            'id_poli' => $this->routeIs('dokter.*') ? ['required', 'integer', 'exists:poli,id'] : ['exclude'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'no_ktp.unique' => 'NIK sudah digunakan oleh akun lain.',
            'no_ktp.regex' => 'NIK harus terdiri dari 16 digit angka.',
            'no_hp.regex' => 'Nomor HP harus terdiri dari 10 sampai 15 digit angka.',
            'id_poli.exists' => 'Poli yang dipilih tidak tersedia.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['nama' => 'Nama lengkap', 'alamat' => 'Alamat', 'no_ktp' => 'NIK', 'no_hp' => 'Nomor HP', 'id_poli' => 'Poli', 'password' => 'Kata sandi'];
    }
}
