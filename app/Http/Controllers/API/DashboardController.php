<?php

// app/Http/Controllers/API/DashboardController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArsipAktif;
use App\Models\ArsipInaktif;
use App\Models\SuratTugas;
use App\Models\BeritaAcaraPemindahan;
use App\Models\BeritaAcaraPemusnahan;
use App\Models\BeritaAcaraAlihmedia;

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        $user = $request->user();
        
        try {
            // Get counts based on user role
            $arsipAktifQuery = ArsipAktif::query();
            $arsipInaktifQuery = ArsipInaktif::query();
            $suratTugasQuery = SuratTugas::query();
            $beritaPemindahanQuery = BeritaAcaraPemindahan::query();
            $beritaPemusnahanQuery = BeritaAcaraPemusnahan::query();
            $beritaAlihmediaQuery = BeritaAcaraAlihmedia::query();
            
            // Filter based on user role if needed
            if ($user->role !== 'admin') {
                $arsipAktifQuery->where('user_id', $user->id);
                $arsipInaktifQuery->where('user_id', $user->id);
                $suratTugasQuery->where('user_id', $user->id);
                $beritaPemindahanQuery->where('user_id', $user->id);
                $beritaPemusnahanQuery->where('user_id', $user->id);
                $beritaAlihmediaQuery->where('user_id', $user->id);
            }
            
            return response()->json([
                'total_arsip_aktif' => $arsipAktifQuery->count(),
                'total_arsip_inaktif' => $arsipInaktifQuery->count(),
                'total_surat_tugas' => $suratTugasQuery->count(),
                'total_berita_pemindahan' => $beritaPemindahanQuery->count(),
                'total_berita_pemusnahan' => $beritaPemusnahanQuery->count(),
                'total_berita_alihmedia' => $beritaAlihmediaQuery->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'total_arsip_aktif' => 0,
                'total_arsip_inaktif' => 0,
                'total_surat_tugas' => 0,
                'total_berita_pemindahan' => 0,
                'total_berita_pemusnahan' => 0,
                'total_berita_alihmedia' => 0,
            ], 500);
        }
    }
}