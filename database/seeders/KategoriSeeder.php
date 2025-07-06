<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Surat Resmi', 'deskripsi' => 'Dokumen surat resmi perusahaan seperti pengumuman dan edaran'],
            ['nama_kategori' => 'Laporan', 'deskripsi' => 'Laporan bulanan, tahunan, audit, atau kegiatan operasional'],
            ['nama_kategori' => 'Kontrak', 'deskripsi' => 'Kontrak kerjasama, vendor, maupun pihak ketiga'],
            ['nama_kategori' => 'SOP', 'deskripsi' => 'Standard Operating Procedure atau prosedur kerja standar'],
            ['nama_kategori' => 'Invoice', 'deskripsi' => 'Tagihan pembayaran dari vendor atau mitra'],
            ['nama_kategori' => 'Notulen Rapat', 'deskripsi' => 'Catatan hasil rapat divisi maupun manajemen'],
            ['nama_kategori' => 'Dokumen Teknis', 'deskripsi' => 'Spesifikasi teknis, manual, dan gambar teknik'],
            ['nama_kategori' => 'Dokumen Prasarana', 'deskripsi' => 'Dokumen terkait rel, jembatan, sinyal, dan stasiun'],
            ['nama_kategori' => 'Dokumen Sarana', 'deskripsi' => 'Data perawatan lokomotif, kereta, dan peralatan'],
            ['nama_kategori' => 'Dokumen Pelayanan', 'deskripsi' => 'Form, SOP, atau data terkait pelayanan pelanggan'],
        ];


        foreach ($kategori as $kat) {
            Kategori::create($kat);
        }
    }
}
