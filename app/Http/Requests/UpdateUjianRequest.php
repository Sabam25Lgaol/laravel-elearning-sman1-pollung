<?php

namespace App\Http\Requests;

use App\Models\Ujian;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        $ujian = Ujian::with('pelajaran')->find($this->route('id'));

        if (!$ujian || !$ujian->pelajaran) {
            return false;
        }

        return $ujian->pelajaran->guru_id === Auth::id();
    }

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
