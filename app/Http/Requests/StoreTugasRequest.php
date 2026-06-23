<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pelajaran;
use Illuminate\Support\Facades\Auth;

class StoreTugasRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan melakukan request ini.
     * (AUTHORIZATION: Hanya guru pengampu yang boleh menambah tugas)
     */
    public function authorize(): bool
    {
        // Mengambil ID pelajaran dari URL (rute: guru/pelajaran/{id}/tugas)
        $pelajaran_id = $this->route('id');
        $pelajaran = Pelajaran::find($pelajaran_id);

        if (!$pelajaran) {
            return false;
        }

        // Cek apakah guru yang login adalah pengampu pelajaran tersebut
        return $pelajaran->guru_id === Auth::id();
    }

    /**
     * Aturan validasi (Pengecekan Form Tambah Tugas)
     */
    public function rules(): array
    {
        return [
            'judul_tugas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tenggat_waktu' => 'required|date',
            'file_tugas' => 'nullable|mimes:pdf,doc,docx,zip,rar|max:5120', // Maks 5MB
        ];
    }
}
