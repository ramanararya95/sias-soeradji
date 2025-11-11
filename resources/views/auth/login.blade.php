<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAS Soeradji</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url('{{ asset('img/background/background_login.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }
        .bg-white-custom {
            background-color: rgba(255, 255, 255, 0.95);
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

        <!-- Right Section (Form Login) -->
        <div class="w-full md:w-1/2 p-8 bg-white-custom">
            <h2 class="text-center text-lg italic font-medium mb-2">RSUP SOERADJI TIRTONEGORO</h2>
            <p class="text-center text-gray-500 text-sm mb-6">Sistem Input Arsip Soeradji</p>
            <hr class="mb-4">

            <!-- Menampilkan Pesan Error -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <div>
                            <p class="font-medium">Terjadi kesalahan:</p>
                            <ul class="mt-1 text-sm list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username atau Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input id="username" name="username" type="text" required
                            class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan username atau email"
                            value="{{ old('username') }}">
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan password">
                    </div>
                </div>
                
                <div>
                    <label for="captcha" class="block text-sm font-medium text-gray-700 mb-2">Kode Verifikasi</label>
                    <div class="flex items-center space-x-2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-shield-alt text-gray-400"></i>
                            </div>
                            <input id="captcha" name="captcha" type="text" required
                                class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan kode verifikasi">
                        </div>
                        <div class="bg-gray-100 px-3 py-2 rounded-lg">
                            <img src="{{ route('captcha.generate') }}?t={{ time() }}" alt="CAPTCHA" class="h-8" id="captcha-image">
                        </div>
                        <button type="button" onclick="refreshCaptcha()" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                    </div>
                </div>
                    
                <div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        Login
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Lupa password?
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function refreshCaptcha() {
            // Tambahkan timestamp untuk mencegah caching browser
            var timestamp = new Date().getTime();
            document.getElementById('captcha-image').src = '{{ route('captcha.generate') }}?t=' + timestamp;
        }
    </script>
</body>
</html>