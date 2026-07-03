<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pelajaran;
use Illuminate\Support\Facades\Auth;

class StoreUjianRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan (Authorization)
     * Hanya guru pengampu pelajaran yang boleh membuat ujian!
     */
    public function authorize(): bool
    {
        $pelajaran_id = $this->route('id'); // Ambil dari URL
        $pelajaran = Pelajaran::find($pelajaran_id);

        if (!$pelajaran) {
            return false;
        }

        return $pelajaran->guru_id === Auth::id();
    }

    /**
     * Aturan validasi (Pengecekan Form)
     */
    public function rules(): array
    {
        return [
            'judul_ujian' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'durasi' => 'required|numeric|min:5',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
            'acak_soal' => 'nullable|boolean',
            'acak_jawaban' => 'nullable|boolean',
        ];
    }
}
