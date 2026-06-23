<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Auth;

class NilaiTugasRequest extends FormRequest
{
    /**
     * AUTHORIZATION: Pastikan guru yang menilai adalah pengampu pelajaran terkait
     */
    public function authorize(): bool
    {
        $pengumpulan_id = $this->route('pengumpulan_id');
        $pengumpulan = PengumpulanTugas::with('tugas.pelajaran')->find($pengumpulan_id);

        if (!$pengumpulan) {
            return false;
        }

        return $pengumpulan->tugas->pelajaran->guru_id === Auth::id();
    }

    /**
     * Aturan validasi input nilai
     */
    public function rules(): array
    {
        return [
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan_guru' => 'nullable|string',
        ];
    }
}
