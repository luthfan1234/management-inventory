<?php

namespace App\Exports;

use App\Models\Arsip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArsipExport implements FromCollection, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function map($arsip): array
    {
        return [
            $arsip->nomor_arsip,
            $arsip->judul,
            $arsip->divisi->nama_divisi,
            $arsip->kategori->nama_kategori,
            $arsip->uploader->name,
            $arsip->created_at->format('d-m-Y H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Arsip',
            'Judul',
            'Divisi',
            'Kategori',
            'Diupload Oleh',
            'Tanggal Upload',
        ];
    }
}
