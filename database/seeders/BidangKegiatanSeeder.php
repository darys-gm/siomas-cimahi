<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BidangKegiatan;

class BidangKegiatanSeeder extends Seeder
{
    public function run()
    {
        $bidang = [
            'Sosial dan Kemanusiaan',
            'Pendidikan',
            'Kesehatan',
            'Ekonomi dan Kewirausahaan',
            'Lingkungan Hidup',
            'Seni dan Budaya',
            'Olahraga',
            'Keagamaan',
            'Hukum dan HAM',
            'Kepemudaan',
            'Keagamaan dan Kepercayaan',
            'Sosial, Kemanusiaan dan Kemasyarakatan',
            'Pemberdayaan Perempuan, Perlindungan Anak dan Keluarga',
            'Kepemudaan, Olahraga dan Seni Budaya',
            'Pendidikan dan Pemberdayaan SDM',
            'Profesi dan Keahlian',
            'Lingkungan Hidup dan Kebencanaan',
            'Ekonomi, Kewirausahaan dan Pemberdayaan Masyarakat',
            'Kebangsaan dan Bela Negara',
            'Hukum, HAM dan Advokasi Publik'
        ];

        foreach ($bidang as $item) {
            BidangKegiatan::firstOrCreate(['nama' => $item]);
        }
    }
}