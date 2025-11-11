<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generate()
    {
        // Generate CAPTCHA baru
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $captcha = '';
        for ($i = 0; $i < 6; $i++) {
            $captcha .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Simpan di session
        Session::put('captcha', $captcha);
        
        // Buat gambar
        $image = imagecreatetruecolor(150, 50);
        
        // Warna background
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $background_color);
        
        // Warna teks
        $text_color = imagecolorallocate($image, 0, 0, 0);
        
        // Tambahkan noise/garis acak
        for ($i = 0; $i < 5; $i++) {
            $line_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($image, 0, rand(0, 50), 150, rand(0, 50), $line_color);
        }
        
        // Tambahkan titik acak
        for ($i = 0; $i < 50; $i++) {
            $pixel_color = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($image, rand(0, 150), rand(0, 50), $pixel_color);
        }
        
        // Gunakan font built-in
        imagestring($image, 5, 40, 15, $captcha, $text_color);
        
        // Set header untuk menampilkan gambar
        header('Content-type: image/jpeg');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Tampilkan gambar
        imagejpeg($image);
        
        // Hapus gambar dari memori
        imagedestroy($image);
    }
    
    public function refresh()
    {
        // Generate CAPTCHA baru
        $this->generate();
        
        // Kirim response sebagai JSON
        return response()->json([
            'success' => true,
            'captcha' => Session::get('captcha')
        ]);
    }
}