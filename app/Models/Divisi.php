<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';

    protected $fillable = [
        'nama_divisi',
        'deskripsi'
    ];

    public function arsip()
    {
        return $this->hasMany(Arsip::class);
    }
}
