<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;

class UpdateTugasRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan melakukan request ini.
     * (AUTHORIZATION: Hanya guru pembuat tugas yang boleh mengeditnya)
     */
    public function authorize(): bool
    {
        // Mengambil ID tugas dari URL (rute: guru/tugas/{id}/update)
        $tugas_id = $this->route('id');

        // Eager load relasi pelajaran untuk mengecek guru pengampunya
        $tugas = Tugas::with('pelajaran')->find($tugas_id);

        if (!$tugas) {
            return false;
        }

        // Cek apakah guru yang login adalah pembuat tugas (pengampu pelajaran)
        return $tugas->pelajaran->guru_id === Auth::id();
    }

    /**
     * Aturan validasi (Pengecekan Form Edit Tugas)
     */
    public function rules(): array
    {
        return [
            'judul_tugas' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tenggat_waktu' => 'required|date',
            'file_tugas' => 'nullable|mimes:pdf,doc,docx,zip,rar|max:10240', // Maks 10MB
        ];
    }
}
