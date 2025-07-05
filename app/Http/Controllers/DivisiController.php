<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DivisiController extends Controller
{
    public function index()
    {
        return Inertia::render('Divisi/Index', [
            'divisi' => Divisi::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi',
            'deskripsi' => 'nullable|string',
        ]);

        Divisi::create($request->all());

        return redirect()->route('divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi,' . $divisi->id,
            'deskripsi' => 'nullable|string',
        ]);

        $divisi->update($request->all());

        return redirect()->route('divisi.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        if ($divisi->arsip()->count() > 0) {
            return redirect()->route('divisi.index')
                ->with('error', 'Divisi tidak dapat dihapus karena masih digunakan oleh arsip.');
        }

        $divisi->delete();

        return redirect()->route('divisi.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }
}
