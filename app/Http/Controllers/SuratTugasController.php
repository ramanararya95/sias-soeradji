<?php

namespace App\Http\Controllers;

use App\Models\LogSuratTugas;
use App\Services\SuratTugasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuratTugasController extends Controller
{
    protected $suratTugasService;

    public function __construct(SuratTugasService $suratTugasService)
    {
        $this->suratTugasService = $suratTugasService;
    }

    // Tampilkan form registrasi
    public function index()
    {
        // Cek apakah ada data form di session untuk edit
        $formData = session('surat_tugas_form_data', null);
        
        return view('surat_tugas.form', compact('formData'));
    }

    // Proses form untuk preview
    public function preview(Request $request)
    {
        $data = $request->all();
        $templateType = $data['jenis_surat'] ?? 1;

        // Simpan data ke session untuk digunakan di halaman preview dan generate
        session(['surat_tugas_preview_data' => $data]);
        
        // Simpan juga data form untuk kemungkinan edit
        session(['surat_tugas_form_data' => $data]);

        return redirect()->route('surat_tugas.preview_page');
    }

    // Tampilkan halaman preview
    public function showPreview()
    {
        if (!session()->has('surat_tugas_preview_data')) {
            return redirect()->route('surat_tugas.form')->with('error', 'Data tidak ditemukan, silakan isi form kembali.');
        }

        $data = session('surat_tugas_preview_data');
        $templateType = $data['jenis_surat'] ?? 1;
        $previewHtml = $this->suratTugasService->generatePreviewHtml($data, $templateType);

        return view('surat_tugas.preview', compact('previewHtml', 'data'));
    }

    /**
     * Generate file Word
     */
    public function generateWord(Request $request)
    {
        if (!session()->has('surat_tugas_preview_data')) {
            return redirect()->route('surat_tugas.form')->with('error', 'Data tidak ditemukan.');
        }

        $data = session('surat_tugas_preview_data');
        $templateType = $data['jenis_surat'] ?? 1;
        $filename = 'ST_' . date('YmdHis') . '.docx';

        try {
            // Generate file Word
            $filePath = $this->suratTugasService->generateWord($data, $templateType, $filename);
            
            // Simpan nama file ke session untuk digunakan saat menyimpan ke log
            session(['surat_tugas_generated_file' => $filename]);
            
            return response()->download($filePath);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate file Word: ' . $e->getMessage());
        }
    }

    /**
     * Simpan data ke database
     */
    public function save(Request $request)
    {
        if (!session()->has('surat_tugas_preview_data')) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 400);
        }

        $data = session('surat_tugas_preview_data');
        $templateType = $data['jenis_surat'] ?? 1;
        
        // Gunakan file yang sudah di-generate jika ada, atau buat baru
        $filename = session('surat_tugas_generated_file', 'ST_' . date('YmdHis') . '.docx');
        
        // Jika file belum di-generate, generate sekarang
        if (!session()->has('surat_tugas_generated_file')) {
            try {
                $this->suratTugasService->generateWord($data, $templateType, $filename);
                session(['surat_tugas_generated_file' => $filename]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal generate file Word: ' . $e->getMessage()
                ], 500);
            }
        }

        try {
            // Siapkan data untuk disimpan
            $saveData = [
                'jenis_naskah' => $data['jenis_naskah'] ?? '',
                'pengirim' => $data['pengirim'] ?? '',
                'nomor_pengirim' => $data['nomor_pengirim'] ?? '',
                'tanggal_surat' => $data['tanggal_surat'] ?? '',
                'hal' => $data['hal'] ?? '',
                
                // Data Pegawai 1 (selalu ada)
                'nama_gelar1' => $data['nama_gelar'][0] ?? '',
                'nip1' => $data['nip'][0] ?? '',
                'pangkat_golongan1' => $data['pangkat_golongan'][0] ?? '',
                'jabatan1' => $data['jabatan'][0] ?? '',
                
                // Data Pegawai 2 (opsional)
                'nama_gelar2' => isset($data['nama_gelar'][1]) ? $data['nama_gelar'][1] : null,
                'nip2' => isset($data['nip'][1]) ? $data['nip'][1] : null,
                'pangkat_golongan2' => isset($data['pangkat_golongan'][1]) ? $data['pangkat_golongan'][1] : null,
                'jabatan2' => isset($data['jabatan'][1]) ? $data['jabatan'][1] : null,
                
                'filename_word' => $filename,
                'template_type' => $templateType,
                'html' => $this->suratTugasService->generatePreviewHtml($data, $templateType),
            ];

            // Simpan data tugas
            for ($i = 1; $i <= 6; $i++) {
                $saveData["untuk_$i"] = $data["untuk_$i"] ?? '';
            }

            // Debug: Log data yang akan disimpan
            Log::info('Data yang akan disimpan ke database:', $saveData);


            // Di method save, tambahkan ini sebelum LogSuratTugas::create($saveData)
            Log::info('Database connection: ' . config('database.default'));
            Log::info('Database name: ' . config('database.connections.' . config('database.default') . '.database'));

            // Cek apakah tabel ada
            if (\Schema::hasTable('log_surat_tugas')) {
                Log::info('Table log_surat_tugas exists');
            } else {
                Log::error('Table log_surat_tugas does not exist');
            }

            // Cek apakah bisa connect ke database
            try {
                \DB::connection()->getPdo();
                Log::info('Database connection successful');
            } catch (\Exception $e) {
                Log::error('Database connection failed: ' . $e->getMessage());
            }

            // Simpan ke database
            $logSuratTugas = LogSuratTugas::create($saveData);
            
            // Debug: Log ID yang berhasil disimpan
            Log::info('Data berhasil disimpan dengan ID: ' . $logSuratTugas->id);

            // Hapus session
            session()->forget('surat_tugas_preview_data');
            session()->forget('surat_tugas_generated_file');
            session()->forget('surat_tugas_form_data');

            return response()->json([
                'success' => true,
                'message' => 'Surat Tugas berhasil disimpan!',
                'id' => $logSuratTugas->id
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error saving surat tugas: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Jika gagal, kembalikan response error
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan surat tugas: ' . $e->getMessage()
            ], 500);
        }
    }

    // Endpoint untuk autocomplete pencarian pegawai
    public function searchPegawai(Request $request)
    {
        // Debug: Log request
        Log::info('Request searchPegawai: ' . json_encode($request->all()));
        
        // Handle debug requests
        if ($request->has('debug')) {
            if ($request->debug == 'file') {
                $filePath = public_path('files/09. PETA PEGAWAI BULAN SEPTEMBER 2025.xlsx');
                $fileExists = file_exists($filePath);
                
                return response()->json([
                    'file_exists' => $fileExists,
                    'file_path' => $filePath,
                    'file_size' => $fileExists ? filesize($filePath) : 0,
                    'last_modified' => $fileExists ? date('Y-m-d H:i:s', filemtime($filePath)) : 'N/A'
                ]);
            } else if ($request->debug == 'sample') {
                $pegawaiData = $this->suratTugasService->getPegawaiData();
                return response()->json([
                    'total' => count($pegawaiData),
                    'data' => array_slice($pegawaiData, 0, 5)
                ]);
            }
        }
        
        $term = $request->get('term');
        $results = $this->suratTugasService->searchPegawai($term);
        
        // Debug: Log response
        Log::info('Response searchPegawai: ' . json_encode($results));
        
        return response()->json($results);
    }
}