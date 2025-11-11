@extends('layouts.app')

@section('title', 'Watermark Gambar - SIAS 2025')
@section('header-title', 'Watermark Gambar')
@section('header-subtitle', 'Tambahkan watermark pada gambar Anda')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-image text-blue-500 mr-2"></i> Watermark Gambar
            </h2>
            <div class="flex gap-2">
                <a href="javascript:location.reload()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i> Reload
                </a>
                <a href="{{ route('watermark.logs.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-list mr-2"></i> Log Watermark
                </a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                {!! session('success') !!}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif
        
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">
                <i class="fas fa-info-circle mr-2"></i> Informasi Watermark
            </h3>
            <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                <li>• Watermark akan ditambahkan secara otomatis menggunakan gambar</li>
                <li>• Ukuran watermark adalah 1:2 dari lebar gambar asli</li>
                <li>• Watermark ditempatkan di tengah gambar</li>
                <li>• Format yang didukung: JPG, JPEG, PNG</li>
                <li>• Setelah preview, Anda bisa langsung download hasilnya</li>
                <li>• Setelah melihat preview, download preview, jangan lupa untuk disimpan ya agar pekerjaan tidak hilang</li>
            </ul>
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-upload mr-2"></i> Upload Gambar
            </h3>
            <form method="post" enctype="multipart/form-data" id="watermarkForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pilih Gambar (JPG/JPEG/PNG):
                    </label>
                    <div class="relative">
                        <input type="file" id="file" name="file" accept=".jpg,.jpeg,.png" required class="hidden">
                        <label for="file" id="fileLabel" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">Klik untuk memilih gambar</span> atau seret ke sini
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG</p>
                            </div>
                        </label>
                    </div>
                    <div class="mt-2 hidden" id="progressContainer">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" id="progressBar" style="width: 0%"></div>
                        </div>
                        <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-1" id="progressText">0%</p>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Gambar Asli</h3>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex items-center justify-center min-h-[300px] watermark-preview-image" id="originalPreview">
                    <div class="text-center">
                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                        <span class="text-gray-500">Preview gambar asli</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Hasil Watermark</h3>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex items-center justify-center min-h-[300px] watermark-preview-image" id="watermarkedPreview">
                    <div class="text-center">
                        <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                        <span class="text-gray-500">Preview hasil watermark</span>
                    </div>
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="button" id="previewBtn" disabled class="flex-1 bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i> Preview Watermark
                    </button>
                    <button id="resetBtn" disabled class="bg-gray-500 hover:bg-gray-600 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </button>
                    <button id="saveBtn" disabled style="display: none;" class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i> Simpan ke Log
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Simpan ke Log</h3>
            <span class="text-gray-400 hover:text-gray-600 cursor-pointer" onclick="document.getElementById('confirmModal').style.display='none'">&times;</span>
        </div>
        <div class="mb-4">
            <p class="text-gray-700 dark:text-gray-300 mb-2">Anda dapat mengganti nama file sebelum menyimpan ke log:</p>
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Contoh: SK Pendirian Rumah Sakit Umum Pusat Soeradji Tirtonegoro_1992_Watermark
                </label>
                <input type="text" id="filenameInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Masukkan nama file">
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">File akan disimpan dengan ekstensi .png</p>
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" id="cancelBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">Batal</button>
            <button type="button" id="confirmBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">Simpan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let originalImageData = null;
    let watermarkedImageData = null;
    
    const fileInput = document.getElementById('file');
    const previewBtn = document.getElementById('previewBtn');
    const resetBtn = document.getElementById('resetBtn');
    const saveBtn = document.getElementById('saveBtn');
    const originalPreview = document.getElementById('originalPreview');
    const watermarkedPreview = document.getElementById('watermarkedPreview');
    const watermarkForm = document.getElementById('watermarkForm');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const fileLabel = document.getElementById('fileLabel');
    
    // Event listener untuk file input
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Hanya file JPG, JPEG, dan PNG yang diperbolehkan');
                this.value = ''; // Reset input
                return;
            }
            
            // Update label dengan nama file
            fileLabel.innerHTML = `
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <i class="fas fa-file-image text-3xl text-green-500 mb-2"></i>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">${file.name}</span>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Format: ${file.type}, Ukuran: ${formatFileSize(file.size)}</p>
                </div>
            `;
            
            const reader = new FileReader();
            reader.onload = function(event) {
                // Tampilkan loading
                originalPreview.innerHTML = `
                    <div class="flex flex-col items-center justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                        <span class="mt-2 text-gray-500">Memuat gambar...</span>
                    </div>
                `;
                
                // Simulasi loading
                setTimeout(() => {
                    originalPreview.innerHTML = 
                        `<img src="${event.target.result}" alt="Original" class="max-w-full max-h-full object-contain rounded">`;
                    
                    // Simpan data gambar asli
                    originalImageData = event.target.result;
                    
                    // Aktifkan tombol preview
                    previewBtn.disabled = false;
                    resetBtn.disabled = false;
                }, 1000);
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Format ukuran file
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Event listener untuk tombol preview
    previewBtn.addEventListener('click', function() {
        if (!originalImageData) return;
        
        // Tampilkan loading
        watermarkedPreview.innerHTML = `
            <div class="flex flex-col items-center justify-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                <span class="mt-2 text-gray-500">Menerapkan watermark...</span>
            </div>
        `;
        
        // Buat canvas untuk proses watermark
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.onload = function() {
            // Set ukuran canvas sesuai gambar
            canvas.width = img.width;
            canvas.height = img.height;
            
            // Gambar gambar asli ke canvas
            ctx.drawImage(img, 0, 0);
            
            // Muat watermark
            const watermark = new Image();
            watermark.onload = function() {
                // Hitung ukuran watermark (setengah dari lebar gambar)
                const watermarkWidth = img.width / 2;
                const watermarkHeight = (watermark.height * watermarkWidth) / watermark.width;
                
                // Hitung posisi watermark (tengah)
                const x = (img.width - watermarkWidth) / 2;
                const y = (img.height - watermarkHeight) / 2;
                
                // Terapkan opacity
                ctx.globalAlpha = 0.25;
                
                // Gambar watermark
                ctx.drawImage(watermark, x, y, watermarkWidth, watermarkHeight);
                
                // Tampilkan hasil dengan img element yang dikontrol ukurannya
                watermarkedPreview.innerHTML = `
                    <img src="${canvas.toDataURL('image/png')}" alt="Watermarked" class="max-w-full max-h-full object-contain rounded-lg">
                `;
                
                // Simpan data hasil
                watermarkedImageData = canvas.toDataURL('image/png');
                
                // Aktifkan tombol save
                saveBtn.disabled = false;
                saveBtn.style.display = 'block';
            };
            
            watermark.onerror = function() {
                watermarkedPreview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-2"></i>
                        <span class="text-red-500">Gagal memuat watermark</span>
                    </div>
                `;
            };
            
            watermark.src = '{{ asset("img/watermark_foto/watermark_foto.png") }}';
        };
        
        img.onerror = function() {
            watermarkedPreview.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-2"></i>
                    <span class="text-red-500">Gagal memuat gambar</span>
                </div>
            `;
        };
        
        img.src = originalImageData;
    });
    
    // Event listener untuk tombol reset
    resetBtn.addEventListener('click', function() {
        // Reset preview
        originalPreview.innerHTML = `
            <div class="text-center">
                <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                <span class="text-gray-500">Preview gambar asli</span>
            </div>
        `;
        
        watermarkedPreview.innerHTML = `
            <div class="text-center">
                <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                <span class="text-gray-500">Preview hasil watermark</span>
            </div>
        `;
        
        // Reset data
        originalImageData = null;
        watermarkedImageData = null;
        
        // Reset form
        watermarkForm.reset();
        fileLabel.innerHTML = `
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Klik untuk memilih gambar</span> atau seret ke sini
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Format: JPG, JPEG, PNG</p>
            </div>
        `;
        
        // Disable tombol
        previewBtn.disabled = true;
        resetBtn.disabled = true;
        saveBtn.disabled = true;
        saveBtn.style.display = 'none';
    });
    
    // Event listener untuk modal
    const modal = document.getElementById('confirmModal');
    const modalClose = document.querySelector('.text-gray-400');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmBtn = document.getElementById('confirmBtn');
    const filenameInput = document.getElementById('filenameInput');
    
    // Buka modal saat tombol save diklik
    saveBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
    });
    
    // Tutup modal
    modalClose.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    // Simpan preview
    confirmBtn.addEventListener('click', function() {
        if (!watermarkedImageData) return;
        
        const filename = filenameInput.value || 'watermarked_' + Date.now() + '.png';
        
        // Tampilkan loading
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        
        // Kirim data ke server
        fetch('{{ route("watermark.image.save-preview") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                image_data: watermarkedImageData,
                filename: filename
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tampilkan pesan sukses
                const messageDiv = document.createElement('div');
                messageDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
                messageDiv.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${data.message}`;
                
                // Hapus pesan lama jika ada
                const oldMessage = document.querySelector('.bg-green-100');
                if (oldMessage) {
                    oldMessage.remove();
                }
                
                // Tambahkan pesan baru
                const container = document.querySelector('.container');
                container.insertBefore(messageDiv, container.firstChild);
                
                // Reset form
                resetBtn.click();
                
                // Tutup modal
                modal.style.display = 'none';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan file');
        })
        .finally(() => {
            // Reset tombol
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan ke Log';
        });
    });
    
    // Tutup modal jika klik di luar modal
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>
@endpush