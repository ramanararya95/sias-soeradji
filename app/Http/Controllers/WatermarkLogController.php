<?php

namespace App\Http\Controllers;

use App\Models\WatermarkLogImage;
use App\Models\WatermarkLogText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class WatermarkLogController extends Controller
{
    /**
     * Tampilkan halaman log watermark
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $page = $request->get('page', 1);
        $perPage = 10;
        
        $logs = [];
        $totalItems = 0;
        
        if ($filter === 'all') {
            // Gabungkan query dari kedua tabel
            $imageLogs = WatermarkLogImage::with('user')
                ->select('*', 'watermark_logs_images.id as id', 'watermark_logs_images.created_at as created_at')
                ->addSelect("'image' as source_table");
                
            $textLogs = WatermarkLogText::with('user')
                ->select('*', 'watermark_logs_texts.id as id', 'watermark_logs_texts.created_at as created_at')
                ->addSelect("'text' as source_table");
                
            $allLogs = $imageLogs->unionAll($textLogs)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
                
            $logs = $allLogs->items();
            $totalItems = $allLogs->total();
        } elseif ($filter === 'image') {
            $paginatedLogs = WatermarkLogImage::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
                
            foreach ($paginatedLogs->items() as $log) {
                $log->source_table = 'image';
                $logs[] = $log;
            }
            
            $totalItems = $paginatedLogs->total();
        } elseif ($filter === 'text') {
            $paginatedLogs = WatermarkLogText::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
                
            foreach ($paginatedLogs->items() as $log) {
                $log->source_table = 'text';
                $logs[] = $log;
            }
            
            $totalItems = $paginatedLogs->total();
        }
        
        // Statistik
        $stats = [
            'text' => WatermarkLogText::count(),
            'image' => WatermarkLogImage::count()
        ];
        
        return view('watermark.logs', compact('logs', 'filter', 'page', 'totalItems', 'stats', 'perPage'));
    }
    
    /**
     * Hapus log watermark
     */
    public function destroy(Request $request, $id)
    {
        $filter = $request->get('filter', 'all');
        $page = $request->get('page', 1);
        $sourceTable = $request->get('source', '');
        
        // Tentukan tabel berdasarkan source
        if ($sourceTable === 'text') {
            $log = WatermarkLogText::find($id);
        } elseif ($sourceTable === 'image') {
            $log = WatermarkLogImage::find($id);
        } else {
            // Jika tidak ada source, coba kedua tabel
            $log = WatermarkLogText::find($id);
            
            if (!$log) {
                $log = WatermarkLogImage::find($id);
            }
        }
        
        if (!$log) {
            return redirect()->route('watermark.logs.index', ['filter' => $filter, 'page' => $page])
                ->with('error', 'Log watermark tidak ditemukan');
        }
        
        // Hanya admin yang bisa menghapus
        if (!in_array(Auth::user()->role, ['admin'])) {
            return redirect()->route('watermark.logs.index', ['filter' => $filter, 'page' => $page])
                ->with('error', 'Anda tidak memiliki izin untuk menghapus log watermark');
        }
        
        // Hapus file watermark
        if (Storage::disk('public')->exists($log->file_path)) {
            Storage::disk('public')->delete($log->file_path);
        }
        
        // Hapus dari database
        $log->delete();
        
        return redirect()->route('watermark.logs.index', ['filter' => $filter, 'page' => $page])
            ->with('success', 'Log watermark berhasil dihapus');
    }
}