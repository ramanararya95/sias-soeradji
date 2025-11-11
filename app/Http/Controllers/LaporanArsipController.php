<?php

// app/Http/Controllers/LaporanArsipController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipAktif;
use App\Models\ArsipInaktif;
use App\Models\ArsipVital;
use App\Models\ArsipAlihmedia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArsipExport;

class LaporanArsipController extends Controller
{
    public function index()
    {
        return view('laporan.arsip.index');
    }
    
    public function filter(Request $request)
    {
        $request->validate([
            'jenis_arsip' => 'required|in:aktif,inaktif,vital,alihmedia',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|string',
        ]);
        
        $jenisArsip = $request->jenis_arsip;
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        $status = $request->status;
        
        // Pilih model berdasarkan jenis arsip
        $model = $this->getModelByJenis($jenisArsip);
        
        // Query dasar
        $query = $model::with('user');
        
        // Filter berdasarkan role
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }
        
        // Filter berdasarkan tanggal
        if ($tanggalMulai) {
            $query->whereDate('created_at', '>=', $tanggalMulai);
        }
        
        if ($tanggalSelesai) {
            $query->whereDate('created_at', '<=', $tanggalSelesai);
        }
        
        // Filter berdasarkan status (jika ada)
        if ($status) {
            $query->where('status', $status);
        }
        
        $arsip = $query->orderBy('created_at', 'desc')->get();
        
        return view('laporan.arsip.result', compact('arsip', 'jenisArsip'));
    }
    
    public function pdf(Request $request)
    {
        $request->validate([
            'jenis_arsip' => 'required|in:aktif,inaktif,vital,alihmedia',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|string',
        ]);
        
        $jenisArsip = $request->jenis_arsip;
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        $status = $request->status;
        
        // Pilih model berdasarkan jenis arsip
        $model = $this->getModelByJenis($jenisArsip);
        
        // Query dasar
        $query = $model::with('user');
        
        // Filter berdasarkan role
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }
        
        // Filter berdasarkan tanggal
        if ($tanggalMulai) {
            $query->whereDate('created_at', '>=', $tanggalMulai);
        }
        
        if ($tanggalSelesai) {
            $query->whereDate('created_at', '<=', $tanggalSelesai);
        }
        
        // Filter berdasarkan status (jika ada)
        if ($status) {
            $query->where('status', $status);
        }
        
        $arsip = $query->orderBy('created_at', 'desc')->get();
        
        // Siapkan data untuk PDF
        $data = [
            'arsip' => $arsip,
            'jenisArsip' => $jenisArsip,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'status' => $status,
            'user' => auth()->user(),
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('laporan.arsip.pdf', $data);
        
        // Download PDF
        $filename = 'laporan_arsip_' . $jenisArsip . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
    
    public function excel(Request $request)
    {
        $request->validate([
            'jenis_arsip' => 'required|in:aktif,inaktif,vital,alihmedia',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|string',
        ]);
        
        $jenisArsip = $request->jenis_arsip;
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        $status = $request->status;
        
        // Pilih model berdasarkan jenis arsip
        $model = $this->getModelByJenis($jenisArsip);
        
        // Query dasar
        $query = $model::with('user');
        
        // Filter berdasarkan role
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }
        
        // Filter berdasarkan tanggal
        if ($tanggalMulai) {
            $query->whereDate('created_at', '>=', $tanggalMulai);
        }
        
        if ($tanggalSelesai) {
            $query->whereDate('created_at', '<=', $tanggalSelesai);
        }
        
        // Filter berdasarkan status (jika ada)
        if ($status) {
            $query->where('status', $status);
        }
        
        $arsip = $query->orderBy('created_at', 'desc')->get();
        
        // Siapkan data untuk Excel
        $data = [
            'arsip' => $arsip,
            'jenisArsip' => $jenisArsip,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'status' => $status,
            'user' => auth()->user(),
        ];
        
        // Generate Excel
        $filename = 'laporan_arsip_' . $jenisArsip . '_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ArsipExport($data), $filename);
    }
    
    private function getModelByJenis($jenis)
    {
        switch ($jenis) {
            case 'aktif':
                return ArsipAktif::class;
            case 'inaktif':
                return ArsipInaktif::class;
            case 'vital':
                return ArsipVital::class;
            case 'alihmedia':
                return ArsipAlihmedia::class;
            default:
                return ArsipAktif::class;
        }
    }
}