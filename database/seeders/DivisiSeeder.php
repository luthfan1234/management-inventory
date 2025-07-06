<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisi = [
            ['kode' => 'IT', 'nama' => 'Teknologi Informasi', 'deskripsi' => 'Divisi yang menangani sistem informasi dan infrastruktur digital'],
            ['kode' => 'HRD', 'nama' => 'Sumber Daya Manusia', 'deskripsi' => 'Divisi yang mengelola perekrutan, pelatihan, dan pengembangan SDM'],
            ['kode' => 'FIN', 'nama' => 'Keuangan', 'deskripsi' => 'Divisi yang bertanggung jawab terhadap perencanaan dan pelaporan keuangan'],
            ['kode' => 'OPS', 'nama' => 'Operasi', 'deskripsi' => 'Divisi yang menangani perencanaan dan pelaksanaan operasional kereta'],
            ['kode' => 'PRAS', 'nama' => 'Prasarana', 'deskripsi' => 'Divisi yang menangani infrastruktur rel, jembatan, dan fasilitas stasiun'],
            ['kode' => 'SAR', 'nama' => 'Sarana', 'deskripsi' => 'Divisi yang menangani perawatan dan pengelolaan sarana kereta'],
            ['kode' => 'K3', 'nama' => 'Keselamatan & Keamanan', 'deskripsi' => 'Divisi yang memastikan keselamatan operasional dan keamanan pengguna'],
            ['kode' => 'PUM', 'nama' => 'Umum', 'deskripsi' => 'Divisi yang menangani urusan administrasi umum dan perlengkapan'],
            ['kode' => 'PEL', 'nama' => 'Pelanggan & Pelayanan', 'deskripsi' => 'Divisi yang menangani layanan pelanggan dan peningkatan mutu pelayanan'],
            ['kode' => 'LOG', 'nama' => 'Logistik', 'deskripsi' => 'Divisi yang menangani pengadaan dan distribusi material'],
        ];



        foreach ($divisi as $div) {
            \App\Models\Divisi::create($div);
        }
    }
}
