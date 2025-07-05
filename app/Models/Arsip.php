<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';

    protected $fillable = [
        'nomor_arsip',
        'judul',
        'deskripsi',
        'divisi_id',
        'kategori_id',
        'versi_dokumen',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'uploaded_by'
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
