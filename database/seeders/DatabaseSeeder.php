<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DokumenPersyaratan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin
        User::create([
            'name' => 'Admin Kesbangpol',
            'email' => 'admin@kesbangpol.cimahi',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true
        ]);

        // Seed master data
        $this->call([
            JenisOrmasSeeder::class,
            BidangKegiatanSeeder::class,
        ]);

        // Seed kecamatan
        $kecamatan = ['Cimahi Selatan', 'Cimahi Tengah', 'Cimahi Utara'];
        foreach ($kecamatan as $item) {
            \App\Models\Kecamatan::create(['nama' => $item]);
        }

        // Seed kelurahan
        $kelurahan = [
            'Cimahi Selatan' => ['Cibeber', 'Cimahi', 'Leuwigajah', 'Melong', 'Utama'],
            'Cimahi Tengah' => ['Baros', 'Cimahi', 'Citeureup', 'Karangmekar', 'Padasuka', 'Setiamanah'],
            'Cimahi Utara' => ['Cibabat', 'Cimahi', 'Citeureup', 'Pasirkaliki']
        ];

        foreach ($kelurahan as $kecamatanName => $kelurahans) {
            $kecamatan = \App\Models\Kecamatan::where('nama', $kecamatanName)->first();
            if ($kecamatan) {
                foreach ($kelurahans as $kelurahanName) {
                    \App\Models\Kelurahan::create([
                        'kecamatan_id' => $kecamatan->id,
                        'nama' => $kelurahanName
                    ]);
                }
            }
        }

        // Seed dokumen persyaratan
        $dokumen = [
            'Fotokopi KTP Pengurus',
            'Fotokopi KK Pengurus',
            'Surat Keterangan Domisili',
            'Akta Pendirian ORMAS',
            'Anggaran Dasar dan Anggaran Rumah Tangga',
            'Surat Keterangan dari Kepolisian',
            'Surat Pernyataan Kesanggupan',
            'Fotokopi NPWP',
            'Struktur Organisasi',
            'Proposal Kegiatan'
        ];

        foreach ($dokumen as $item) {
            DokumenPersyaratan::create([
                'nama' => $item,
                'keterangan' => 'Dokumen persyaratan untuk verifikasi ORMAS'
            ]);
        }
    }
}