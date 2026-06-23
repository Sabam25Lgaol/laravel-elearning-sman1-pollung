<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Role (pakai firstOrCreate agar tidak error jika dijalankan berulang kali)
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleGuru = Role::firstOrCreate(['name' => 'Guru']);
        $roleSiswa = Role::firstOrCreate(['name' => 'Siswa']);

        // 2. Mencari akun yang sudah ada, atau membuat baru jika belum ada
        $adminUser = User::firstOrCreate(
            ['email' => 'sabamgaol25@gmail.com'], // Cari berdasarkan email ini
            [
                'name' => 'Administrator',
                'password' => bcrypt('password123'), // Ini hanya dipakai kalau dia belum punya akun
            ]
        );

        // 3. Memberikan role Admin ke akun tersebut
        $adminUser->assignRole($roleAdmin);
    }
}
