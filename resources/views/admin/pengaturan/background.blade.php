@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-700">
                    <i class="fas fa-image text-blue-500 mr-2"></i> Pengaturan Tampilan Login
                </h2>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    <i class="fas fa-user-shield mr-1"></i> Admin Only
                </span>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.pengaturan.background.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Pengaturan Background -->
                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-700 mb-4">
                            <i class="fas fa-desktop text-blue-500 mr-2"></i> Background Login
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="background" class="block text-sm font-medium text-slate-600 mb-1">
                                    Upload Background Baru (jpg, jpeg, png):
                                </label>
                                <input type="file" name="background" id="background" 
                                    class="block w-full text-sm text-slate-700 bg-white border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                @error('background')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
                                <i class="fas fa-upload"></i> Upload Background
                            </button>
                        </div>

                        <!-- Preview Background -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Preview Background Saat Ini:</h4>

                            @if(!empty($user->background))
                                <img src="{{ Storage::url('background/' . $user->background) }}" alt="Background Login"
                                    class="w-full max-h-60 object-cover rounded-lg border border-slate-300 shadow">
                            @else
                                <p class="text-slate-400 italic text-sm">Belum ada Background yang diganti.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Pengaturan Ilustrasi -->
                    <div class="bg-slate-50 p-5 rounded-lg border border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-700 mb-4">
                            <i class="fas fa-image text-green-500 mr-2"></i> Ilustrasi Login
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="ilustrasi" class="block text-sm font-medium text-slate-600 mb-1">
                                    Upload Ilustrasi Baru (jpg, jpeg, png):
                                </label>
                                <input type="file" name="ilustrasi" id="ilustrasi" 
                                    class="block w-full text-sm text-slate-700 bg-white border border-slate-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400">
                                @error('ilustrasi')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow">
                                <i class="fas fa-upload"></i> Upload Ilustrasi
                            </button>
                        </div>

                        <!-- Preview Ilustrasi -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-slate-600 mb-2">Preview ADS saat ini:</h4>

                            @if(!empty($user->login_ilustrasi))
                                <img src="{{ Storage::url('ilustrasi/' . $user->login_ilustrasi) }}" alt="Ilustrasi Login"
                                    class="w-full max-h-60 object-contain rounded-lg border border-slate-300 shadow">
                            @else
                                <img src="{{ asset('img/login_ilustrasi.png') }}" alt="Ilustrasi Default"
                                    class="w-full max-h-60 object-contain rounded-lg border border-slate-300 shadow">
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            <!-- Informasi Penggunaan -->
            <div class="mt-8 bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-700 mb-2">
                    <i class="fas fa-info-circle mr-2"></i> Informasi Penggunaan
                </h3>
                <ul class="text-sm text-blue-600 list-disc pl-5 space-y-1">
                    <li>Anda dapat mengubah background dan ilustrasi halaman login sesuai kebutuhan</li>
                    <li>Fitur ini berguna untuk menampilkan banner event khusus seperti HUT RI, ulang tahun instansi, dll</li>
                    <li>Ukuran file yang direkomendasikan: maksimal 2MB dengan format JPG, JPEG, atau PNG</li>
                    <li>Untuk hasil terbaik, gunakan gambar dengan rasio aspek 16:9 untuk background dan 4:3 untuk ilustrasi</li>
                    <li><strong>Hanya pengguna dengan role admin yang dapat mengakses halaman ini</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection