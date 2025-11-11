<?php

// app/Http/Controllers/LaporanAktivitasController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AktivitasExport;

class LaporanAktivitasController extends Controller
{
    public function index()
    {
        return view('laporan.aktivitas.index');
    }
    
    public function filter(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'user_id' => 'nullable|exists:users,id',
        ]);
        
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        $userId = $request->user_id;
        
        // Query dasar
        $query = Activity::with('user');
        
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
        
        // Filter berdasarkan user (hanya admin)
        if ($userId && auth()->user()->role === 'admin') {
            $query->where('user_id', $userId);
        }
        
        $aktivitas = $query->orderBy('created_at', 'desc')->get();
        
        return view('laporan.aktivitas.result', compact('aktivitas'));
    }
    
    public function pdf(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'user_id' => 'nullable|exists:users,id',
        ]);
        
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        $userId = $request->user_id;
        
        // Query dasar
        $query = Activity::with('user');
        
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
        
        // Filter berdasarkan user (hanya admin)
        if ($userId && auth()->user()->role === 'admin') {
            $query->where('user_id', $userId);
        }
        
        $aktivitas = $query->orderBy('created_at', 'desc')->get();
        
        // Siapkan data untuk PDF
        $data = [
            'aktivitas' => $aktivitas,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'userId' => $userId,
            'user' => auth()->user(),
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('laporan.aktivitas.pdf', $data);
        
        // Download PDF
        $filename = 'laporan_aktivitas_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
    
    public function excel(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'user_id' => 'nullable|exists:users,id',
        ]);
        
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        $userId = $request->user_id;
        
        // Query dasar
        $query = Activity::with('user');
        
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
        
        // Filter berdasarkan user (hanya admin)
        if ($userId && auth()->user()->role === 'admin') {
            $query->where('user_id', $userId);
        }
        
        $aktivitas = $query->orderBy('created_at', 'desc')->get();
        
        // Siapkan data untuk Excel
        $data = [
            'aktivitas' => $aktivitas,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'userId' => $userId,
            'user' => auth()->user(),
        ];
        
        // Generate Excel
        $filename = 'laporan_aktivitas_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new AktivitasExport($data), $filename);
    }
}