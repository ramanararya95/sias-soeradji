<!-- resources/views/laporan/arsip/index.blade.php -->
@extends('layouts.app')

@section('title', 'Laporan Arsip - SIAS Soeradji')

@section('header-title', 'Laporan Arsip')
@section('header-subtitle', 'Cetak Laporan Data Arsip')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Filter Laporan Arsip</h2>
    
    <form action="{{ route('laporan.arsip.filter') }}" method="POST" class="space-y-4">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="jenis_arsip" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Arsip</label>
                <select id="jenis_arsip" name="jenis_arsip" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Jenis Arsip</option>
                    <option value="aktif">Arsip Aktif</option>
                    <option value="inaktif">Arsip Inaktif</option>
                    <option value="vital">Arsip Vital</option>
                    <option value="alihmedia">Arsip Alih Media</option>
                </select>
                @error('jenis_arsip')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('tanggal_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('tanggal_selesai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select id="status" name="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
        </div>
    </form>
    
    <!-- Hasil filter akan ditampilkan di sini -->
    <div id="filter-result" class="mt-6">
        <!-- Hasil akan dimuat melalui AJAX -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const resultContainer = document.getElementById('filter-result');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.text())
        .then(html => {
            resultContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
</script>
@endsection