<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pelajaran;
use Illuminate\Support\Facades\Auth;

class StoreMateriRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan (Authorization)
     * Hanya guru pengampu pelajaran yang boleh menambah materi!
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
     * Aturan validasi (Pengecekan Form Tambah Materi)
     */
    public function rules(): array
    {
        return [
            'judul_materi' => 'required|string|max:255',
            'isi_materi' => 'nullable|string',
            'link_youtube' => 'nullable|url',
            'file_materi' => 'nullable|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,png,jpg,jpeg,gif,zip,rar|max:10240', // Maks 10MB
        ];
    }
}
