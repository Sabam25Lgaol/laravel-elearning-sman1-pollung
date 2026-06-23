<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;

class UpdateMateriRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan (Authorization)
     * Hanya guru pembuat materi yang boleh mengeditnya!
     */
    public function authorize(): bool
    {
        $materi_id = $this->route('id'); // Ambil dari URL
        $materi = Materi::with('pelajaran')->find($materi_id);

        if (!$materi) {
            return false;
        }

        return $materi->pelajaran->guru_id === Auth::id();
    }

    /**
     * Aturan validasi (Pengecekan Form Edit Materi)
     */
    public function rules(): array
    {
        return [
            'judul_materi' => 'required|string|max:255',
            'isi_materi' => 'nullable|string',
            'link_youtube' => 'nullable|url',
            'file_materi' => 'nullable|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240', // Maks 10MB
        ];
    }
}
