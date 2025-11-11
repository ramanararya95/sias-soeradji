<!-- resources/views/berita_acara/pemindahan/create.blade.php -->
@extends('layouts.app')

@section('title', 'Buat Berita Acara Pemindahan - SIAS Soeradji')

@section('header-title', 'Buat Berita Acara Pemindahan')
@section('header-subtitle', 'Form Pembuatan Berita Acara Pemindahan Arsip')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <form action="{{ route('berita_acara.pemindahan.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nomor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Berita Acara</label>
                <input type="text" id="nomor" name="nomor" value="{{ old('nomor') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('nomor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('tanggal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="lokasi_asal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lokasi Asal</label>
                <input type="text" id="lokasi_asal" name="lokasi_asal" value="{{ old('lokasi_asal') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('lokasi_asal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="lokasi_tujuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lokasi Tujuan</label>
                <input type="text" id="lokasi_tujuan" name="lokasi_tujuan" value="{{ old('lokasi_tujuan') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('lokasi_tujuan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="keterangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <a href="{{ route('berita_acara.pemindahan.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg mr-3 transition-colors">
                Batal
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection