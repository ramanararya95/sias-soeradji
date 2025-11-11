<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password | SIAS</title>
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
    .alert {
      transition: all 0.3s ease;
    }
    .submit-button {
      background: linear-gradient(45deg, #6366f1, #4f46e5);
      transition: all 0.3s ease;
    }
    .submit-button:hover {
      background: linear-gradient(45deg, #4f46e5, #4338ca);
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
      <img src="{{ asset('img/login_ilustrasi.png') }}" alt="Ilustrasi" class="w-full max-w-sm">
    </div>

    <!-- Right Section -->
    <div class="w-full md:w-1/2 p-8 bg-white-custom">
      <h2 class="text-center text-lg italic font-medium mb-2">RSUP SOERADJI TIRTONEGORO</h2>
      <p class="text-center text-gray-500 text-sm mb-6">Sistem Input Arsip Soeradji</p>
      <hr class="mb-4">

      <form method="POST" action="{{ route('password.email') }}" class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md space-y-6 mx-auto">
        @csrf
        <h2 class="text-2xl font-bold text-gray-800 text-center">🔐 Lupa Password</h2>
        
        @if ($errors->any())
          <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $errors->first() }}</span>
          </div>
        @endif
        
        @if (session('error'))
          <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
          </div>
        @endif
        
        @if (session('success'))
          <div class="alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
          </div>
        @endif
        
        <p class="text-gray-600 text-center text-sm">Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
        
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
          <input 
            type="email" 
            name="email" 
            required 
            placeholder="Masukkan email Anda" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            value="{{ old('email') }}"
          >
        </div>

        <button 
          type="submit" 
          class="w-full submit-button text-white py-2 rounded-lg font-semibold shadow-md"
        >
          Kirim Link Reset 🔄
        </button>

        <div class="text-sm text-center text-gray-500">
          <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Kembali ke Login</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>