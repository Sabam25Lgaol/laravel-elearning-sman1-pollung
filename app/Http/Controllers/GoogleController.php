<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // SUNTIKAN ANTI-MACET: Tambahkan stateless() agar sesi tidak hilang di jalan
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 1. Cek apakah user sudah pernah login pakai Google sebelumnya (sudah terikat)
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user);
                return redirect('/dashboard');
            } else {
                // 2. Cek apakah emailnya SUDAH DIDAFTARKAN oleh Admin
                $existingUser = User::where('email', $googleUser->email)->first();

                if ($existingUser) {
                    // Jika email terdaftar, gabungkan/update akunnya dengan ID Google
                    $existingUser->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                    Auth::login($existingUser);
                    return redirect('/dashboard');
                } else {
                    // 3. GEMBOK TERPASANG 🔒: Tolak akses jika email tidak ada di database!
                    // Dikembalikan ke '/' dengan membawa pesan error
                    return redirect('/')->with('error', 'Akses Ditolak! Akun Gmail Anda belum didaftarkan oleh Administrator SMAN 1 Pollung.');
                }
            }

        } catch (\Exception $e) {
            // Jika terjadi error sistem (misal koneksi Google putus), kembalikan ke halaman awal dengan rapi
            return redirect('/')->with('error', 'Terjadi kesalahan saat menghubungi server Google. Silakan coba lagi.');
        }
    }
}
