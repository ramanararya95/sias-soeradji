<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratTugas;
use Carbon\Carbon;
use File;
use Response;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Exception;

class LogSuratTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Query untuk data log_surat_tugas
        $suratTugasList = SuratTugas::orderBy('created_at', 'desc')->paginate(10);
        
        // Query untuk statistik
        $total_count = SuratTugas::count();
        $generated_count = SuratTugas::whereNotNull('filename_word')->where('filename_word', '!=', '')->count();
        $draft_count = $total_count - $generated_count;
        $today_count = SuratTugas::whereDate('created_at', Carbon::today())->count();
        $yesterday_count = SuratTugas::whereDate('created_at', Carbon::yesterday())->count();
        
        // Hitung persentase perubahan
        $today_change = 0;
        if ($yesterday_count > 0) {
            $today_change = round((($today_count - $yesterday_count) / $yesterday_count) * 100);
        } elseif ($today_count > 0) {
            $today_change = 100; // Jika kemarin 0 dan hari ini ada, maka 100% peningkatan
        }
        
        return view('log_surat_tugas.index', compact(
            'suratTugasList', 
            'total_count', 
            'generated_count', 
            'draft_count', 
            'today_count', 
            'today_change'
        ));
    }
    
    /**
     * View the Word document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $surat = SuratTugas::findOrFail($id);
        
        if (!$surat->filename_word) {
            return redirect()->back()->with('error', 'File Word tidak ditemukan');
        }
        
        $filePath = public_path('uploads/surat_tugas/' . $surat->filename_word);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }
        
        // Convert to HTML for viewing
        try {
            $phpWord = IOFactory::load($filePath);
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
            $htmlContent = '';
            
            // Capture the output
            ob_start();
            $htmlWriter->save('php://output');
            $htmlContent = ob_get_contents();
            ob_end_clean();
            
            return view('log_surat_tugas.view', compact('htmlContent', 'surat'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka file: ' . $e->getMessage());
        }
    }
    
    /**
     * Download the Word document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $surat = SuratTugas::findOrFail($id);
        
        if (!$surat->filename_word) {
            return redirect()->back()->with('error', 'File Word tidak ditemukan');
        }
        
        $filePath = public_path('uploads/surat_tugas/' . $surat->filename_word);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server');
        }
        
        return Response::download($filePath, $surat->filename_word);
    }
    
    /**
     * Generate Word document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:surat_tugas,id'
        ]);
        
        $surat = SuratTugas::findOrFail($request->id);
        
        try {
            // Tentukan template berdasarkan tipe
            $templateType = $surat->template_type ?? 1;
            $templateFile = public_path('templates/template_st' . $templateType . '.docx');
            
            if (!file_exists($templateFile)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template tidak ditemukan'
                ]);
            }
            
            // Load template
            $phpWord = IOFactory::load($templateFile);
            
            // Replace placeholders with actual data
            $this->replacePlaceholders($phpWord, $surat);
            
            // Generate filename
            $tanggalFile = Carbon::parse($surat->created_at)->format('Ymd');
            $namaPegawai1 = $this->cleanNameForFilename($surat->nama_gelar1);
            
            if ($templateType == 1) {
                $namaFilePegawai = $namaPegawai1;
            } elseif ($templateType == 2) {
                $namaPegawai2 = $this->cleanNameForFilename($surat->nama_gelar2 ?? '');
                $namaFilePegawai = !empty($namaPegawai2) ? $namaPegawai1 . '_' . $namaPegawai2 : $namaPegawai1;
            } else {
                $namaFilePegawai = $namaPegawai1 . '_dll';
            }
            
            $filename = "ST_{$tanggalFile}_{$namaFilePegawai}.docx";
            
            // Create directory if it doesn't exist
            $directory = public_path('uploads/surat_tugas');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            
            // Save the file
            $filePath = $directory . '/' . $filename;
            $phpWord->save($filePath, 'Word2007');
            
            // Update database
            $surat->filename_word = $filename;
            $surat->file_size = filesize($filePath);
            $surat->save();
            
            return response()->json([
                'success' => true,
                'message' => 'File Word berhasil dibuat',
                'filename' => $filename
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat file Word: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update filename.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateFilename(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:surat_tugas,id',
            'filename' => 'required|string|max:255'
        ]);
        
        $surat = SuratTugas::findOrFail($request->id);
        
        try {
            // Get old file path
            $oldFilePath = public_path('uploads/surat_tugas/' . $surat->filename_word);
            
            // Get new file path
            $newFilename = $request->filename;
            $newFilePath = public_path('uploads/surat_tugas/' . $newFilename);
            
            // Rename file if it exists
            if (file_exists($oldFilePath)) {
                rename($oldFilePath, $newFilePath);
            }
            
            // Update database
            $surat->filename_word = $newFilename;
            $surat->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Nama file berhasil diperbarui'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui nama file: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:surat_tugas,id'
        ]);
        
        $surat = SuratTugas::findOrFail($request->id);
        
        try {
            // Delete file if it exists
            if ($surat->filename_word) {
                $filePath = public_path('uploads/surat_tugas/' . $surat->filename_word);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            // Delete from database
            $surat->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Surat tugas berhasil dihapus'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus surat tugas: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Replace placeholders in the template with actual data.
     *
     * @param  \PhpOffice\PhpWord\PhpWord  $phpWord
     * @param  \App\Models\SuratTugas  $surat
     * @return void
     */
    private function replacePlaceholders($phpWord, $surat)
    {
        $sections = $phpWord->getSections();
        
        foreach ($sections as $section) {
            $elements = $section->getElements();
            
            foreach ($elements as $element) {
                if (method_exists($element, 'getText')) {
                    $text = $element->getText();
                    
                    // Replace common placeholders
                    $text = str_replace('{nama_pegawai1}', $surat->nama_gelar1 ?? '', $text);
                    $text = str_replace('{nama_pegawai2}', $surat->nama_gelar2 ?? '', $text);
                    $text = str_replace('{hal}', $surat->hal ?? '', $text);
                    $text = str_replace('{tanggal}', Carbon::parse($surat->created_at)->format('d F Y'), $text);
                    
                    // Update the element with replaced text
                    $element->setText($text);
                }
            }
        }
    }
    
    /**
     * Clean name for filename.
     *
     * @param  string  $name
     * @return string
     */
    private function cleanNameForFilename($name)
    {
        // Hapus karakter khusus kecuali spasi
        $name = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
        // Ganti spasi dengan underscore
        $name = str_replace(' ', '_', $name);
        // Hapus underscore ganda
        $name = preg_replace('/_+/', '_', $name);
        // Trim underscore di awal/akhir
        $name = trim($name, '_');
        return $name;
    }

    /**
     * Show the form for creating a new multi-person surat tugas.
     *
     * @return \Illuminate\Http\Response
     */
    public function createMultiple()
    {
        return view('log_surat_tugas.create_multiple');
    }

    /**
     * Store a newly created multi-person surat tugas in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMultiple(Request $request)
    {
        // Validasi input
        $request->validate([
            'hal' => 'required|string|max:255',
            'nama_pegawai' => 'required|array|min:3', // Minimal 3 pegawai
            'nama_pegawai.*' => 'required|string|max:255',
        ]);

        try {
            // Siapkan data untuk disimpan
            $data = [
                'hal' => $request->hal,
                'template_type' => 3, // 3 untuk template banyak orang (template_st3.docx)
            ];

            // Ambil nama pegawai dan simpan ke kolom yang sesuai
            $namaPegawai = $request->nama_pegawai;
            for ($i = 1; $i <= 30; $i++) { // Sesuaikan dengan jumlah maksimal kolom di DB Anda
                $column = 'nama_gelar' . $i;
                if (isset($namaPegawai[$i - 1])) {
                    $data[$column] = $namaPegawai[$i - 1];
                } else {
                    $data[$column] = null; // Kosongkan jika tidak ada
                }
            }

            // Simpan ke database
            SuratTugas::create($data);

            return redirect()->route('log_surat_tugas.index')->with('success', 'Surat tugas untuk banyak pegawai berhasil dibuat!');

        } catch (\Exception $e) {
            // Jika terjadi error, kembalikan ke form dengan error message
            return redirect()->back()->with('error', 'Gagal menyimpan surat tugas: ' . $e->getMessage())->withInput();
        }
    }


}