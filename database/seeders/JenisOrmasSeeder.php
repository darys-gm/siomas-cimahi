<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisOrmas;

class JenisOrmasSeeder extends Seeder
{
    public function run()
    {
        // Hanya menyisakan data yang dipakai sesuai database asli
        $jenis = [
            'Yayasan',
            'Perkumpulan',
        ];

        foreach ($jenis as $item) {
            JenisOrmas::firstOrCreate(['nama' => $item]);
        }
    }
}