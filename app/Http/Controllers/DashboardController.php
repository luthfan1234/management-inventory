<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Divisi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        // 1. Jika role adalah 'admin', tampilkan dashboard analitik.
        if ($user->role === 'admin') {
            $stats = [
                'totalArsip' => Arsip::count(),
                'totalDivisi' => Divisi::count(),
                'totalKategori' => Kategori::count(),
                'totalUkuranFile' => Arsip::sum('file_size'),
                'arsipTerbaru' => Arsip::with('divisi')->latest()->take(5)->get(),
                'arsipPerDivisi' => Divisi::withCount('arsip')->orderBy('nama')->get(),
                'arsipPerKategori' => Kategori::withCount('arsip')->orderBy('nama_kategori')->get(),
            ];

            // Render ke file Vue: Dashboard.vue
            return Inertia::render('Dashboard', [
                'stats' => $stats,
            ]);
        }

        // 2. Jika role adalah 'user', tampilkan dashboard pencarian.
        $query = Arsip::with(['divisi', 'kategori']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_arsip', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->divisi_id) {
            $query->where('divisi_id', $request->divisi_id);
        }
        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $arsip = $query->latest()->paginate(10)->withQueryString();
        $divisi = Divisi::all();
        $kategori = Kategori::all();

        // Render ke file Vue baru: User/DashboardUser.vue
        return Inertia::render('User/DashboardUser', [
            'arsip' => $arsip,
            'divisi' => $divisi,
            'kategori' => $kategori,
            'filters' => $request->only(['search', 'divisi_id', 'kategori_id'])
        ]);
    }
}
