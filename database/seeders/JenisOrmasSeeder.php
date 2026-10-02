<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisOrmas;

class JenisOrmasSeeder extends Seeder
{
    public function run()
    {
        $jenis = [
            'Organisasi Kemasyarakatan',
            'Organisasi Profesi',
            'Organisasi Keagamaan',
            'Organisasi Pemuda',
            'Organisasi Perempuan',
            'Organisasi Olahraga',
            'Organisasi Seni dan Budaya',
            'Organisasi Lingkungan Hidup',
            'Organisasi Pendidikan',
            'Organisasi Sosial',
            'Yayasan',
            'Perkumpulan'
        ];

        foreach ($jenis as $item) {
            JenisOrmas::firstOrCreate(['nama' => $item]);
        }
    }
}