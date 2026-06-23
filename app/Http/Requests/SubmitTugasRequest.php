<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitTugasRequest extends FormRequest
{
    /**
     * Otorisasi: Biarkan true karena rute ini sudah dilindungi oleh middleware role:Siswa
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi pengumpulan tugas
     */
    public function rules(): array
    {
        return [
            'file_jawaban' => 'required|mimes:pdf,doc,docx,zip,rar|max:5120',
            'catatan_siswa' => 'nullable|string',
        ];
    }
}
