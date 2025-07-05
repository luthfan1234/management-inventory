<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisi = [
            ['kode' => 'IT', 'nama' => 'IT', 'deskripsi' => 'Divisi Teknologi Informasi'],
            ['kode' => 'HRD', 'nama' => 'HRD', 'deskripsi' => 'Divisi Human Resource'],
            ['kode' => 'FIN', 'nama' => 'Finance', 'deskripsi' => 'Divisi Keuangan'],
            ['kode' => 'MKT', 'nama' => 'Marketing', 'deskripsi' => 'Divisi Pemasaran'],
            ['kode' => 'OPS', 'nama' => 'Operations', 'deskripsi' => 'Divisi Operasional'],
        ];


        foreach ($divisi as $div) {
            \App\Models\Divisi::create($div);
        }
    }
}
