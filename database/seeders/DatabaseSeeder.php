<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin
        User::updateOrCreate(
            ['username' => 'adminksbgpl6202'],
            [
                'name'      => 'Admin Kesbangpol',
                'email'     => 'admin@kesbangpol.cimahi',
                'password'  => Hash::make('password123'),
                'role'      => 'admin',
                'is_active' => 1,
            ]
        );

        // Seed master data
        $this->call([
            JenisOrmasSeeder::class,
            BidangKegiatanSeeder::class,
        ]);

        // Seed kecamatan
        // Menggunakan firstOrCreate agar tidak duplikat jika dijalankan ulang
        $kecamatan = ['Cimahi Selatan', 'Cimahi Tengah', 'Cimahi Utara'];
        foreach ($kecamatan as $item) {
            Kecamatan::firstOrCreate(['nama' => $item]);
        }

        // Seed kelurahan sesuai data terbaru
        $kelurahan = [
            'Cimahi Selatan' => ['Cibeber', 'Cibeureum', 'Leuwigajah', 'Melong', 'Utama'],
            'Cimahi Tengah'  => ['Baros', 'Cigugur Tengah', 'Cimahi', 'Karangmekar', 'Padasuka', 'Setiamanah'],
            'Cimahi Utara'   => ['Cibabat', 'Cipageran', 'Citeureup', 'Pasirkaliki']
        ];

        foreach ($kelurahan as $kecamatanName => $kelurahans) {
            $kec = Kecamatan::where('nama', $kecamatanName)->first();
            if ($kec) {
                foreach ($kelurahans as $kelurahanName) {
                    Kelurahan::firstOrCreate([
                        'kecamatan_id' => $kec->id,
                        'nama' => $kelurahanName
                    ]);
                }
            }
        }
    }
}