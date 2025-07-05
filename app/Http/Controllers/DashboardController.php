<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Divisi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $stats = [
            'totalArsip' => Arsip::count(),
            'totalDivisi' => Divisi::count(),
            'totalKategori' => Kategori::count(),
            'totalUkuranFile' => Arsip::sum('file_size'),
            'arsipTerbaru' => Arsip::with('divisi')->latest()->take(5)->get(),
            'arsipPerDivisi' => Divisi::withCount('arsip')->orderBy('nama')->get(),
            'arsipPerKategori' => Kategori::withCount('arsip')->orderBy('nama_kategori')->get(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
        ]);
    }
}
