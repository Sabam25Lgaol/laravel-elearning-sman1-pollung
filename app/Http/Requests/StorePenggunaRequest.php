<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenggunaRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        return true; // WAJIB DIUBAH KE TRUE agar satpamnya mengizinkan orang lewat
    }

    /**
     * Aturan validasi (Pengecekan Berkas)
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:Admin,Guru,Siswa',
            // Kita juga bisa menambahkan validasi untuk NIS/NIP jika mau, tapi ini sudah cukup mengikuti yang lama.
        ];
    }
}
