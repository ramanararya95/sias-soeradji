<?php

namespace App\Services;

use App\Models\WatermarkLogImage;
use App\Models\WatermarkLogText;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
// Perbaiki import berikut
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Mpdf\Mpdf;
use PhpOffice\PhpWord\IOFactory;

class WatermarkService
{
    protected $imageManager;
    
    public function __construct()
    {
        // Pastikan menggunakan namespace yang benar
        $this->imageManager = new ImageManager(new Driver());
    }
    
    /**
     * Proses watermark untuk gambar
     */
    public function processImageWatermark(UploadedFile $file, $userId = null)
    {
        $originalName = $file->getClientOriginalName();
        $fileExt = strtolower($file->getClientOriginalExtension());
        
        // Validasi tipe file
        if (!in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
            throw new \Exception('Hanya file JPG, JPEG, dan PNG yang diperbolehkan');
        }
        
        // Simpan file upload sementara
        $uploadPath = 'temp/' . uniqid() . '.' . $fileExt;
        $file->storeAs('public', $uploadPath);
        $fullUploadPath = storage_path('app/public/' . $uploadPath);
        
        // Nama file hasil
        $outputName = 'watermarked_' . uniqid() . '.' . $fileExt;
        $outputPath = 'watermarks/images/' . $outputName;
        $fullOutputPath = storage_path('app/public/' . $outputPath);
        
        // Proses watermark
        $this->addImageWatermark($fullUploadPath, $fullOutputPath);
        
        // Dapatkan ukuran file
        $fileSize = filesize($fullOutputPath);
        
        // Simpan ke database
        $log = WatermarkLogImage::create([
            'original_filename' => $originalName,
            'watermarked_filename' => $outputName,
            'file_size' => $fileSize,
            'file_type' => 'image',
            'file_path' => $outputPath,
            'user_id' => $userId
        ]);
        
        // Hapus file upload sementara
        if (file_exists($fullUploadPath)) {
            unlink($fullUploadPath);
        }
        
        return $log;
    }
    
    /**
     * Proses watermark untuk teks (PDF/DOCX)
     */
    public function processTextWatermark(UploadedFile $file, $userId = null)
    {
        $originalName = $file->getClientOriginalName();
        $fileExt = strtolower($file->getClientOriginalExtension());
        
        // Validasi tipe file
        if (!in_array($fileExt, ['pdf', 'docx'])) {
            throw new \Exception('Hanya file PDF dan DOCX yang diperbolehkan');
        }
        
        // Simpan file upload sementara
        $uploadPath = 'temp/' . $originalName;
        $file->storeAs('public', $uploadPath);
        $fullUploadPath = storage_path('app/public/' . $uploadPath);
        
        // Nama file hasil
        $outputName = pathinfo($originalName, PATHINFO_FILENAME) . "_watermarked.pdf";
        $outputPath = 'watermarks/texts/' . $outputName;
        $fullOutputPath = storage_path('app/public/' . $outputPath);
        
        // Proses watermark berdasarkan tipe file
        if ($fileExt === 'pdf') {
            $this->addPdfWatermark($fullUploadPath, $fullOutputPath);
        } elseif ($fileExt === 'docx') {
            $this->addDocxWatermark($fullUploadPath, $fullOutputPath);
        }
        
        // Dapatkan ukuran file
        $fileSize = filesize($fullOutputPath);
        
        // Simpan ke database
        $log = WatermarkLogText::create([
            'original_filename' => $originalName,
            'watermarked_filename' => $outputName,
            'file_size' => $fileSize,
            'file_type' => $fileExt,
            'file_path' => $outputPath,
            'user_id' => $userId
        ]);
        
        // Hapus file upload sementara
        if (file_exists($fullUploadPath)) {
            unlink($fullUploadPath);
        }
        
        return $log;
    }
    
    /**
     * Tambahkan watermark ke gambar
     */
    private function addImageWatermark($inputPath, $outputPath)
    {
        // Path watermark
        $watermarkPath = public_path('img/watermark_foto/watermark_foto.png');
        
        if (!file_exists($watermarkPath)) {
            throw new \Exception('File watermark tidak ditemukan: ' . $watermarkPath);
        }
        
        // Baca gambar asli dengan GD
        $imageInfo = getimagesize($inputPath);
        if (!$imageInfo) {
            throw new \Exception('Gagal membaca informasi gambar: ' . $inputPath);
        }
        
        $imageType = $imageInfo[2];
        
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPath);
                break;
            default:
                throw new \Exception('Format gambar tidak didukung');
        }
        
        if (!$image) {
            throw new \Exception('Gagal membuat gambar dari file: ' . $inputPath);
        }
        
        // Baca watermark dengan GD
        $watermark = imagecreatefrompng($watermarkPath);
        if (!$watermark) {
            imagedestroy($image);
            throw new \Exception('Gagal membaca file watermark: ' . $watermarkPath);
        }
        
        // Dapatkan ukuran gambar asli
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        
        // Dapatkan ukuran watermark asli
        $watermarkWidth = imagesx($watermark);
        $watermarkHeight = imagesy($watermark);
        
        // Kriteria 1: Perbandingan ukuran foto dan watermark adalah 2:1 untuk lebar
        $newWatermarkWidth = $imageWidth / 2;
        
        // Kriteria 2: Tinggi watermark menyesuaikan otomatis (mempertahankan aspect ratio)
        $newWatermarkHeight = ($watermarkHeight * $newWatermarkWidth) / $watermarkWidth;
        
        // Buat gambar watermark baru dengan ukuran yang diinginkan
        $resizedWatermark = imagecreatetruecolor($newWatermarkWidth, $newWatermarkHeight);
        if (!$resizedWatermark) {
            imagedestroy($image);
            imagedestroy($watermark);
            throw new \Exception('Gagal membuat gambar watermark yang di-resize');
        }
        
        // Aktifkan transparansi untuk gambar watermark
        imagealphablending($resizedWatermark, false);
        imagesavealpha($resizedWatermark, true);
        
        // Isi dengan transparan
        $transparent = imagecolorallocatealpha($resizedWatermark, 255, 255, 255, 127);
        imagefill($resizedWatermark, 0, 0, $transparent);
        
        // Resize watermark dengan menjaga transparansi
        imagecopyresampled($resizedWatermark, $watermark, 0, 0, 0, 0, $newWatermarkWidth, $newWatermarkHeight, $watermarkWidth, $watermarkHeight);
        
        // Hapus background putih dari watermark
        $this->removeWhiteBackground($resizedWatermark);
        
        // Buat layer untuk opacity
        $opacityLayer = imagecreatetruecolor($newWatermarkWidth, $newWatermarkHeight);
        if (!$opacityLayer) {
            imagedestroy($image);
            imagedestroy($watermark);
            imagedestroy($resizedWatermark);
            throw new \Exception('Gagal membuat layer opacity');
        }
        
        // Aktifkan transparansi untuk layer opacity
        imagealphablending($opacityLayer, false);
        imagesavealpha($opacityLayer, true);
        
        // Isi dengan transparan
        $transparentLayer = imagecolorallocatealpha($opacityLayer, 255, 255, 255, 127);
        imagefill($opacityLayer, 0, 0, $transparentLayer);
        
        // Tempelkan watermark ke layer opacity
        imagealphablending($opacityLayer, true);
        imagecopy($opacityLayer, $resizedWatermark, 0, 0, 0, 0, $newWatermarkWidth, $newWatermarkHeight);
        
        // Kriteria 3: Terapkan opacity 25% ke seluruh layer
        $this->applyOpacity($opacityLayer, 25);
        
        // Kriteria 4: Hitung posisi watermark (tengah)
        $destX = ($imageWidth - $newWatermarkWidth) / 2;
        $destY = ($imageHeight - $newWatermarkHeight) / 2;
        
        // Aktifkan blending untuk gambar asli
        imagealphablending($image, true);
        
        // Tempelkan watermark dengan opacity ke gambar asli
        imagecopy($image, $opacityLayer, $destX, $destY, 0, 0, $newWatermarkWidth, $newWatermarkHeight);
        
        // Pastikan folder output ada
        $outputDir = dirname($outputPath);
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        
        // Simpan hasil
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                if (!imagejpeg($image, $outputPath, 90)) {
                    throw new \Exception('Gagal menyimpan gambar JPEG');
                }
                break;
            case IMAGETYPE_PNG:
                if (!imagepng($image, $outputPath, 9)) {
                    throw new \Exception('Gagal menyimpan gambar PNG');
                }
                break;
        }
        
        // Hapus resource
        imagedestroy($image);
        imagedestroy($watermark);
        imagedestroy($resizedWatermark);
        imagedestroy($opacityLayer);
    }
    
    /**
     * Hapus background putih dari watermark
     */
    private function removeWhiteBackground($image)
    {
        // Dapatkan lebar dan tinggi gambar
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Loop melalui setiap piksel
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                // Dapatkan warna piksel
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $a = ($rgb >> 24) & 0x7F;
                
                // Jika piksel putih (atau mendekati putih), buat transparan
                if ($r > 240 && $g > 240 && $b > 240) {
                    // Buat warna transparan
                    $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
                    // Set piksel menjadi transparan
                    imagesetpixel($image, $x, $y, $transparent);
                }
            }
        }
    }
    
    /**
     * Terapkan opacity ke gambar
     */
    private function applyOpacity(&$image, $opacity)
    {
        // Dapatkan lebar dan tinggi gambar
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Buat gambar baru untuk hasil
        $result = imagecreatetruecolor($width, $height);
        if (!$result) {
            throw new \Exception('Gagal membuat gambar hasil opacity');
        }
        
        // Aktifkan transparansi
        imagealphablending($result, false);
        imagesavealpha($result, true);
        
        // Isi dengan transparan
        $transparent = imagecolorallocatealpha($result, 255, 255, 255, 127);
        imagefill($result, 0, 0, $transparent);
        
        // Loop melalui setiap piksel
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                // Dapatkan warna piksel asli
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $a = ($rgb >> 24) & 0x7F;
                
                // Hitung alpha baru berdasarkan opacity yang diinginkan
                $newAlpha = round($a * ($opacity / 100));
                
                // Buat warna baru dengan alpha yang dihitung
                $color = imagecolorallocatealpha($result, $r, $g, $b, $newAlpha);
                
                // Set piksel di gambar hasil
                imagesetpixel($result, $x, $y, $color);
            }
        }
        
        // Salin hasil kembali ke gambar asli
        imagealphablending($image, true);
        imagecopy($image, $result, 0, 0, 0, 0, $width, $height);
        
        // Hapus resource
        imagedestroy($result);
    }
    
    /**
     * Tambahkan watermark ke PDF
     */
    private function addPdfWatermark($inputPath, $outputPath)
    {
        try {
            // --- METODE 1: mPDF dengan Font Arial ---
            $mpdf = new Mpdf([
                'default_font' => 'arial',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
                'watermark_font' => 'arial',
                'watermarkTextAlpha' => 0.05,
                'showWatermarkText' => true,
                'watermarkText' => 'KEMENTERIAN KESEHATAN',
                'watermarkFontSize' => 36,
                'fontDir' => [public_path('fonts/arial/')], 
                'fontdata' => [
                    'arial' => [
                        'R' => 'arial.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ]
                ]
            ]);
            
            // Coba baca file PDF dengan mPDF
            try {
                $pageCount = $mpdf->SetSourceFile($inputPath);
                
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $mpdf->ImportPage($i);
                    $mpdf->AddPage();
                    $mpdf->UseTemplate($tplId);
                }
                
                // Pastikan folder watermark ada
                $outputDir = dirname($outputPath);
                if (!file_exists($outputDir)) {
                    mkdir($outputDir, 0777, true);
                }
                
                // Simpan file dengan path lengkap
                $mpdf->Output($outputPath, 'F');
                
                // Verifikasi file tersimpan
                if (!file_exists($outputPath)) {
                    throw new \Exception("Gagal menyimpan file watermark ke: " . $outputPath);
                }
                
                return true;
            } catch (\Exception $mpdfException) {
                // Jika gagal dengan mPDF, coba dengan metode alternatif
                try {
                    // --- METODE 2: mPDF Alternatif dengan Font Arial ---
                    $mpdf = new Mpdf([
                        'default_font' => 'arial',
                        'mode' => 'utf-8',
                        'format' => 'A4',
                        'margin_left' => 10,
                        'margin_right' => 10,
                        'margin_top' => 10,
                        'margin_bottom' => 10,
                        'margin_header' => 10,
                        'margin_footer' => 10,
                        'fontDir' => [public_path('fonts/arial/')], 
                        'fontdata' => [
                            'arial' => [
                                'R' => 'arial.ttf',
                                'useOTL' => 0xFF,
                                'useKashida' => 75,
                            ]
                        ]
                    ]);
                    
                    $mpdf->SetWatermarkText('KEMENTERIAN KESEHATAN', 0.5);
                    $mpdf->showWatermarkText = true;
                    $mpdf->watermark_font = 'arial';
                    
                    $mpdf->AddPage();
                    $fileSize = filesize($inputPath);
                    $fileSizeFormatted = $this->formatFileSize($fileSize);
                    
                    // Tambahkan informasi dokumen dengan watermark
                    $html = '
                    <div style="text-align: center; margin-top: 50px;">
                        <div style="color: #cccccc; font-size: 48px; font-family: Arial, sans-serif; font-weight: normal; transform: rotate(-45deg); display: inline-block; margin: 50px 0; opacity: 0.5;">KEMENTERIAN KESEHATAN</div>
                    </div>
                    <div style="margin-top: 100px; text-align: center;">
                        <h3>Informasi Dokumen</h3>
                        <table style="margin: 20px auto; border-collapse: collapse; width: 80%;">
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Nama File</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">' . basename($inputPath) . '</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Ukuran File</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">' . $fileSizeFormatted . '</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Format</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">PDF</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Status</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">Watermark berhasil ditambahkan</td>
                            </tr>
                        </table>
                        <p style="margin-top: 30px;">File asli telah diproses dan watermark telah ditambahkan.</p>
                        <p>Silakan download file hasil watermark untuk melihat dokumen lengkap.</p>
                    </div>';
                    
                    $mpdf->WriteHTML($html);
                    $mpdf->Output($outputPath, 'F');
                    
                    // Verifikasi file tersimpan
                    if (!file_exists($outputPath)) {
                        throw new \Exception("Gagal menyimpan file watermark ke: " . $outputPath);
                    }
                    
                    return true;
                } catch (\Exception $alternativeException) {
                    // Jika masih gagal dengan Arial, gunakan font default
                    try {
                        // --- METODE 3: mPDF dengan Font Default ---
                        $mpdf = new Mpdf([
                            'default_font' => 'dejavusans',
                            'mode' => 'utf-8',
                            'format' => 'A4',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 10,
                            'margin_bottom' => 10,
                            'margin_header' => 10,
                            'margin_footer' => 10,
                        ]);
                        
                        $mpdf->SetWatermarkText('KEMENTERIAN KESEHATAN', 0.5);
                        $mpdf->showWatermarkText = true;
                        $mpdf->watermark_font = 'dejavusans';
                        
                        $mpdf->AddPage();
                        $fileSize = filesize($inputPath);
                        $fileSizeFormatted = $this->formatFileSize($fileSize);
                        
                        $html = '
                        <div style="text-align: center; margin-top: 50px;">
                            <div style="color: #cccccc; font-size: 48px; font-family: Arial, sans-serif; font-weight: normal; transform: rotate(-45deg); display: inline-block; margin: 50px 0; opacity: 0.5;">KEMENTERIAN KESEHATAN</div>
                        </div>
                        <div style="margin-top: 100px; text-align: center;">
                            <h3>Informasi Dokumen</h3>
                            <table style="margin: 20px auto; border-collapse: collapse; width: 80%;">
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Nama File</td>
                                    <td style="border: 1px solid #ddd; padding: 8px;">' . basename($inputPath) . '</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Ukuran File</td>
                                    <td style="border: 1px solid #ddd; padding: 8px;">' . $fileSizeFormatted . '</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Format</td>
                                    <td style="border: 1px solid #ddd; padding: 8px;">PDF</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">Status</td>
                                    <td style="border: 1px solid #ddd; padding: 8px;">Watermark berhasil ditambahkan (font default)</td>
                                </tr>
                            </table>
                            <p style="margin-top: 30px;">File asli telah diproses dan watermark telah ditambahkan.</p>
                            <p>Catatan: Font Arial tidak tersedia, menggunakan font default.</p>
                        </div>';
                        
                        $mpdf->WriteHTML($html);
                        $mpdf->Output($outputPath, 'F');
                        
                        // Verifikasi file tersimpan
                        if (!file_exists($outputPath)) {
                            throw new \Exception("Gagal menyimpan file watermark ke: " . $outputPath);
                        }
                        
                        return true;
                    } catch (\Exception $defaultException) {
                        throw new \Exception("Gagal memproses PDF dengan semua metode: " . $mpdfException->getMessage() . " | " . $alternativeException->getMessage() . " | " . $defaultException->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            throw new \Exception("Gagal memproses PDF: " . $e->getMessage());
        }
    }
    
    /**
     * Tambahkan watermark ke DOCX
     */
    private function addDocxWatermark($inputPath, $outputPath)
    {
        $mpdf = new Mpdf([
            'default_font' => 'arial',
            'mode' => 'utf-8',
            'fontDir' => [public_path('fonts/arial/')], 
            'fontdata' => [
                'arial' => [
                    'R' => 'arial.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ]
            ],
            'watermark_font' => 'arial',
            'watermarkFontSize' => 36
        ]);
        $mpdf->SetWatermarkText('KEMENTERIAN KESEHATAN', 0.1);
        $mpdf->showWatermarkText = true;
        
        $phpWord = IOFactory::load($inputPath);
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        ob_start();
        $htmlWriter->save('php://output');
        $html = ob_get_clean();
        $mpdf->WriteHTML($html);
        
        // Pastikan folder watermark ada
        $outputDir = dirname($outputPath);
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        
        // Simpan file dengan path lengkap
        $mpdf->Output($outputPath, 'F');
        
        // Verifikasi file tersimpan
        if (!file_exists($outputPath)) {
            throw new \Exception("Gagal menyimpan file watermark ke: " . $outputPath);
        }
        
        return true;
    }
    
    /**
     * Format ukuran file
     */
    private function formatFileSize($bytes)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
    
    /**
     * Simpan preview watermark gambar
     */
    public function saveImagePreview($imageData, $filename = null, $userId = null)
    {
        // Ambil data base64 dari preview
        $imageData = $imageData;
        
        // Ambil nama file dari parameter atau gunakan default
        $filename = $filename ?: 'watermarked_' . uniqid() . '.png';
        
        // Sanitasi nama file
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Pastikan nama file memiliki ekstensi .png
        if (!str_ends_with(strtolower($filename), '.png')) {
            $filename .= '.png';
        }
        
        // Hapus prefix data URL jika ada
        if (strpos($imageData, 'data:image/') === 0) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
        }
        
        // Decode base64
        $decodedData = base64_decode($imageData);
        if ($decodedData === false) {
            throw new \Exception('Gagal decode base64 image data');
        }
        
        // Generate path output
        $outputPath = 'watermarks/images/' . $filename;
        $fullOutputPath = storage_path('app/public/' . $outputPath);
        
        // Pastikan folder watermark ada
        $outputDir = dirname($fullOutputPath);
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        
        // Simpan file hasil preview langsung
        if (file_put_contents($fullOutputPath, $decodedData) === false) {
            throw new \Exception("Gagal menyimpan file gambar: " . $fullOutputPath);
        }
        
        // Dapatkan ukuran file
        $fileSize = filesize($fullOutputPath);
        
        // Simpan ke database
        $log = WatermarkLogImage::create([
            'original_filename' => 'preview_image.png',
            'watermarked_filename' => $filename,
            'file_size' => $fileSize,
            'file_type' => 'image',
            'file_path' => $outputPath,
            'user_id' => $userId
        ]);
        
        return $log;
    }
}