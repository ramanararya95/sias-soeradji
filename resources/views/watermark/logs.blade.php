@extends('layouts.app')

@section('title', 'Log Watermark - SIAS 2025')
@section('header-title', 'Log Watermark')
@section('header-subtitle', 'Riwayat watermark yang telah dibuat')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-history text-blue-500 mr-2"></i> Log Watermark
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-2">
                <label for="filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Jenis:</label>
                <select id="filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" onchange="window.location.href='{{ route("watermark.logs.index") }}?filter='+this.value+'&page=1'">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="text" {{ $filter === 'text' ? 'selected' : '' }}>Teks (PDF/DOCX)</option>
                    <option value="image" {{ $filter === 'image' ? 'selected' : '' }}>Gambar</option>
                </select>
            </div>
            
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Menampilkan {{ count($logs) }} dari {{ $totalItems }} entri
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <h3 class="text-3xl font-bold">{{ $stats['text'] }}</h3>
                <p class="text-blue-100">Watermark Teks</p>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <h3 class="text-3xl font-bold">{{ $stats['image'] }}</h3>
                <p class="text-green-100">Watermark Gambar</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if(count($logs) > 0)
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ukuran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $log->watermarked_filename }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $category = ($log->file_type === 'pdf' || $log->file_type === 'docx') ? 'text' : 'image';
                                        $label = ($log->file_type === 'pdf' || $log->file_type === 'docx') ? 'TXT' : 'IMAGE';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $category === 'text' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ formatFileSize($log->file_size) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $log->file_path) }}" download class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        @if(in_array(Auth::user()->role, ['admin']))
                                            <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="confirmDelete({{ $log->id }}, '{{ $log->source_table }}')">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak Ada Data</h3>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada log watermark yang tersedia untuk filter yang dipilih.</p>
                </div>
            @endif
        </div>
        
        @if($totalItems > $perPage)
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Menampilkan {{ ($page - 1) * $perPage + 1 }} hingga {{ min($page * $perPage, $totalItems) }} dari {{ $totalItems }} hasil
                </div>
                <div class="flex gap-1">
                    <button onclick="window.location.href='{{ route("watermark.logs.index") }}?filter={{ $filter }}&page={{ max(1, $page - 1) }}'" {{ $page <= 1 ? 'disabled' : '' }} class="px-3 py-1 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                    
                    @for($i = 1; $i <= ceil($totalItems / $perPage); $i++)
                        @if($i == 1 || $i == ceil($totalItems / $perPage) || ($i >= $page - 2 && $i <= $page + 2))
                            <button onclick="window.location.href='{{ route("watermark.logs.index") }}?filter={{ $filter }}&page={{ $i }}'" class="px-3 py-1 text-sm {{ $i == $page ? 'bg-blue-500 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600' }} rounded-md">
                                {{ $i }}
                            </button>
                        @elseif($i == $page - 3 || $i == $page + 3)
                            <span class="px-3 py-1 text-sm text-gray-500">...</span>
                        @endif
                    @endfor
                    
                    <button onclick="window.location.href='{{ route("watermark.logs.index") }}?filter={{ $filter }}&page={{ min(ceil($totalItems / $perPage), $page + 1) }}'" {{ $page >= ceil($totalItems / $perPage) ? 'disabled' : '' }} class="px-3 py-1 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
            <span class="text-gray-400 hover:text-gray-600 cursor-pointer" onclick="closeModal()">&times;</span>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin menghapus log watermark ini? File watermark juga akan dihapus.</p>
        </div>
        <div class="flex justify-end gap-2">
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors" onclick="closeModal()">Batal</button>
            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors" id="confirmDeleteBtn">Ya, Hapus</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let deleteId = null;
let deleteSource = null;

function confirmDelete(id, source) {
    deleteId = id;
    deleteSource = source;
    document.getElementById('confirmModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
    deleteId = null;
    deleteSource = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteId !== null) {
        window.location.href = '{{ route("watermark.logs.destroy", ":id") }}'.replace(':id', deleteId) + '?source=' + deleteSource + '&filter={{ $filter }}&page={{ $page }}';
    }
});

// Tutup modal jika klik di luar modal
window.onclick = function(event) {
    const modal = document.getElementById('confirmModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Format ukuran file
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>
@endpush