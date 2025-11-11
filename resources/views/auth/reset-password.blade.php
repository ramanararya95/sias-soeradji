<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password | SIAS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: url('{{ asset('img/background/background_login.jpg') }}') no-repeat center center fixed;
      background-size: cover;
    }
    .bg-white-custom {
      background-color: rgba(255, 255, 255, 0.95);
    }
    .captcha-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .captcha-image {
      border: 1px solid #ccc;
      border-radius: 4px;
      height: 50px;
    }
    .refresh-btn {
      cursor: pointer;
      color: #3b82f6;
    }
    .refresh-btn:hover {
      color: #2563eb;
    }
    .reset-button {
      background: linear-gradient(45deg, #eab308, #ca8a04);
      transition: all 0.3s ease;
    }
    .reset-button:hover {
      background: linear-gradient(45deg, #ca8a04, #a16207);
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-6xl flex shadow-lg rounded-xl overflow-hidden">
    <!-- Left Section -->
    <div class="hidden md:flex w-1/2 bg-white p-10 flex-col justify-center">
      <div class="mb-6">
        <img src="{{ asset('img/logo-soeradji.png') }}" alt="Logo Soeradji" class="h-14 mb-2">
        <h1 class="text-sm font-bold leading-tight">SISTEM INPUT<br>ARSIP SOERADJI</h1>
      </div>
      <img src="{{ asset('img/login_ilustrasi.png') }}" alt="Ilustrasi" class="w-full max-w-sm object-contain">
    </div>

    <!-- Right Section -->
    <div class="w-full md:w-1/2 p-8 bg-white-custom">
      <h2 class="text-center text-lg italic font-medium mb-2">RSUP SOERADJI TIRTONEGORO</h2>
      <p class="text-center text-gray-500 text-sm mb-6">Sistem Input Arsip Soeradji</p>
      <hr class="mb-4">

      @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
          <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
          <input type="password" name="password" placeholder="Masukkan Password Baru" required autocomplete="off"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
          <input type="password" name="password_confirmation" placeholder="Masukkan Kembali Password Baru" required autocomplete="off"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <!-- CAPTCHA Section -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Verifikasi Keamanan</label>
          <div class="captcha-container">
            <img src="{{ route('captcha.generate') }}" alt="CAPTCHA" class="captcha-image" id="captchaImage">
            <i class="fas fa-sync-alt refresh-btn" onclick="refreshCaptcha()" title="Refresh CAPTCHA"></i>
          </div>
          <input type="text" name="captcha" placeholder="Masukkan kode di atas" required autocomplete="off"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2">
        </div>
        
        <div class="text-right">
          <a href="{{ route('login') }}" class="text-xs text-blue-600 hover:underline">Kembali ke Halaman Login</a>
        </div>
        
        <button type="submit" class="w-full reset-button text-white py-2 rounded font-medium">
          Reset Password
        </button>
     </form>
    </div>
  </div>

  <script>
    function refreshCaptcha() {
      // Tambahkan parameter acak untuk mencegah caching
      var timestamp = new Date().getTime();
      document.getElementById('captchaImage').src = '{{ route('captcha.generate') }}?t=' + timestamp;
    }
    
    // Refresh otomatis saat halaman dimuat ulang
    window.onload = function() {
      refreshCaptcha();
    };
  </script>
</body>
</html>