<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Divisi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Exports\ArsipExport;
use Maatwebsite\Excel\Facades\Excel;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $query = Arsip::with(['divisi', 'kategori', 'uploader']);

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

        return Inertia::render('Arsip/Index', [
            'arsip' => $arsip,
            'divisi' => $divisi,
            'kategori' => $kategori,
            'filters' => $request->only(['search', 'divisi_id', 'kategori_id'])
        ]);
    }

    public function create()
    {
        return Inertia::render('Arsip/Create', [
            'divisi' => Divisi::all(),
            'kategori' => Kategori::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_arsip' => 'required|string|max:255|unique:arsip',
            'judul' => 'required|string|max:255',
            'divisi_id' => 'required|exists:divisi,id',
            'kategori_id' => 'required|exists:kategori,id',
            'versi_dokumen' => 'required|string|max:50',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('arsip', $fileName, 'public');

        Arsip::create([
            'nomor_arsip' => $request->nomor_arsip,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'divisi_id' => $request->divisi_id,
            'kategori_id' => $request->kategori_id,
            'versi_dokumen' => $request->versi_dokumen,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id()
        ]);

        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil ditambahkan');
    }

    public function show(Arsip $arsip)
    {
        $arsip->load(['divisi', 'kategori', 'uploader']);
        return Inertia::render('Arsip/Show', ['arsip' => $arsip]);
    }

    public function edit(Arsip $arsip)
    {
        return Inertia::render('Arsip/Edit', [
            'arsip' => $arsip,
            'divisi' => Divisi::all(),
            'kategori' => Kategori::all()
        ]);
    }

    public function update(Request $request, Arsip $arsip)
    {
        $request->validate([
            'nomor_arsip' => 'required|string|max:255|unique:arsip,nomor_arsip,' . $arsip->id,
            'judul' => 'required|string|max:255',
            'divisi_id' => 'required|exists:divisi,id',
            'kategori_id' => 'required|exists:kategori,id',
            'versi_dokumen' => 'required|string|max:50',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        $updateData = $request->only(['nomor_arsip', 'judul', 'deskripsi', 'divisi_id', 'kategori_id', 'versi_dokumen']);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($arsip->file_path);
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('arsip', $fileName, 'public');
            $updateData['file_path'] = $filePath;
            $updateData['file_name'] = $fileName;
            $updateData['file_type'] = $file->getClientOriginalExtension();
            $updateData['file_size'] = $file->getSize();
        }

        $arsip->update($updateData);
        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil diperbarui');
    }

    public function destroy(Arsip $arsip)
    {
        Storage::disk('public')->delete($arsip->file_path);
        $arsip->delete();
        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil dihapus');
    }

    public function download(Arsip $arsip)
    {
        $filePath = storage_path('app/public/' . $arsip->file_path);
        if (file_exists($filePath)) {
            return response()->download($filePath, $arsip->file_name);
        }
        return redirect()->back()->with('error', 'File tidak ditemukan');
    }

    public function preview(Arsip $arsip)
    {
        if ($arsip->file_type !== 'pdf') {
            abort(404, 'Hanya file PDF yang dapat di-preview.');
        }
        $filePath = storage_path('app/public/' . $arsip->file_path);
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }
        return response()->file($filePath, ['Content-Disposition' => 'inline; filename="' . $arsip->file_name . '"']);
    }

    public function export(Request $request)
    {
        $query = Arsip::with(['divisi', 'kategori', 'uploader']);

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

        $query->latest();

        return Excel::download(new ArsipExport($query), 'laporan-arsip-' . date('d-m-Y') . '.xlsx');
    }
}
