<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Surat Resmi', 'deskripsi' => 'Dokumen surat resmi perusahaan'],
            ['nama_kategori' => 'Laporan', 'deskripsi' => 'Dokumen laporan bulanan/tahunan'],
            ['nama_kategori' => 'Kontrak', 'deskripsi' => 'Dokumen kontrak dan perjanjian'],
            ['nama_kategori' => 'SOP', 'deskripsi' => 'Standard Operating Procedure'],
            ['nama_kategori' => 'Invoice', 'deskripsi' => 'Dokumen tagihan dan invoice'],
        ];

        foreach ($kategori as $kat) {
            Kategori::create($kat);
        }
    }
}
