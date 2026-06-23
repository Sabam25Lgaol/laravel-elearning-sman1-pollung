<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubmitUjianRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        // KUNCI: Hanya user yang login dan memiliki role Siswa yang boleh submit ujian
        return Auth::check() && Auth::user()->hasRole('Siswa');
    }

    /**
     * Aturan validasi ketat untuk submit jawaban ujian.
     */
    public function rules(): array
    {
        return [
            'jawaban'   => 'required|array',
            'jawaban.*' => 'nullable', // Diizinkan nullable agar siswa tetap bisa mengosongkan/melewati soal yang sulit
        ];
    }

    /**
     * Pesan error kustom yang rapi jika terjadi anomali request saat demo.
     */
    public function messages(): array
    {
        return [
            'jawaban.required' => 'Gagal mengirim! Struktur data jawaban ujian wajib ada.',
            'jawaban.array'    => 'Format data jawaban yang dikirimkan tidak valid.',
        ];
    }
}
