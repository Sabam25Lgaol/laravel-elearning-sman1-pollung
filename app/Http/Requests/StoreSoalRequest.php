<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;

class StoreSoalRequest extends FormRequest
{
    /**
     * AUTHORIZATION: Hanya guru pengampu ujian yang boleh menambah soal
     */
    public function authorize(): bool
    {
        $ujian_id = $this->route('id');
        $ujian = Ujian::with('pelajaran')->find($ujian_id);

        if (!$ujian) {
            return false;
        }

        return $ujian->pelajaran->guru_id === Auth::id();
    }

    /**
     * Aturan validasi pembuatan soal ujian
     */
    public function rules(): array
    {
        $rules = [
            'jenis_soal' => 'required|in:pilihan_ganda,essay',
            'pertanyaan' => 'required|string',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Validasi bersyarat: Jika jenis_soal yang dipilih adalah pilihan ganda
        if ($this->input('jenis_soal') === 'pilihan_ganda') {
            $rules['pilihan_a'] = 'required|string';
            $rules['pilihan_b'] = 'required|string';
            $rules['pilihan_c'] = 'required|string';
            $rules['pilihan_d'] = 'required|string';
            $rules['kunci_jawaban'] = 'required|array|min:1';
        }

        return $rules;
    }
}
