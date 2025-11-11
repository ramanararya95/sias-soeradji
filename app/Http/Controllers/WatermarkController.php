<?php

namespace App\Http\Controllers;

use App\Services\WatermarkService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WatermarkController extends Controller
{
    protected $watermarkService;
    
    public function __construct(WatermarkService $watermarkService)
    {
        $this->watermarkService = $watermarkService;
    }
    
    /**
     * Tampilkan halaman watermark gambar
     */
    public function imageIndex()
    {
        return view('watermark.image');
    }
    
    /**
     * Tampilkan halaman watermark teks
     */
    public function textIndex()
    {
        return view('watermark.text');
    }
    
    /**
     * Proses watermark gambar
     */
    public function processImage(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpg,jpeg,png|max:10240'
            ]);
            
            $userId = Auth::id();
            $log = $this->watermarkService->processImageWatermark($request->file('file'), $userId);
            
            return response()->json([
                'success' => true,
                'message' => "Watermark berhasil ditambahkan! <div class='download-info'>
                            <a href='" . asset('storage/' . $log->file_path) . "' download class='download-btn'>
                                <i class='fas fa-download'></i> Download Hasil
                            </a>
                            <span class='filename'>" . $log->watermarked_filename . "</span>
                        </div>",
                'data' => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Simpan preview watermark gambar
     */
    public function saveImagePreview(Request $request)
    {
        try {
            $request->validate([
                'image_data' => 'required|string',
                'filename' => 'nullable|string|max:255'
            ]);
            
            $userId = Auth::id();
            $log = $this->watermarkService->saveImagePreview(
                $request->input('image_data'),
                $request->input('filename'),
                $userId
            );
            
            return response()->json([
                'success' => true,
                'message' => "Gambar berhasil disimpan ke log! <div class='download-info'>
                            <a href='" . asset('storage/' . $log->file_path) . "' download class='download-btn'>
                                <i class='fas fa-download'></i> Download Hasil
                            </a>
                            <span class='filename'>" . $log->watermarked_filename . "</span>
                        </div>",
                'data' => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Proses watermark teks
     */
    public function processText(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf,docx|max:10240'
            ]);
            
            $userId = Auth::id();
            $log = $this->watermarkService->processTextWatermark($request->file('file'), $userId);
            
            return redirect()->back()->with([
                'success' => 'File berhasil diproses!',
                'downloadLink' => asset('storage/' . $log->file_path),
                'fileName' => $log->watermarked_filename
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}