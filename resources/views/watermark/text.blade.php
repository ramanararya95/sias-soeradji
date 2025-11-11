@extends('layouts.app')

@section('title', 'Watermark Document - SIAS 2025')
@section('header-title', 'Watermark Document')
@section('header-subtitle', 'Tambahkan watermark pada dokumen PDF atau DOCX')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">
                <i class="fas fa-file-alt text-blue-500 mr-2"></i> Watermark Document
            </h2>
            <p class="text-gray-600 dark:text-gray-400">Tambahkan watermark "KEMENTERIAN KESEHATAN" pada dokumen PDF atau DOCX Anda</p>
            <div class="mt-4">
                <a href="{{ route('watermark.logs.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-list mr-2"></i> Log Watermark
                </a>
            </div>
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-cloud-upload mr-2"></i> Upload File untuk Watermark
            </h3>
            <form method="post" enctype="multipart/form-data" id="uploadForm" action="{{ route('watermark.text.process') }}">
                @csrf
                <!-- Area Upload -->
                <div class="mb-4">
                    <div id="uploadArea" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Klik atau tarik file ke sini</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Format yang didukung: PDF, DOCX (Maks. 10MB)</p>
                        </div>
                        <input type="file" name="file" id="file" accept=".pdf,.docx" required class="hidden">
                    </div>
                </div>
                
                <!-- File Info -->
                <div id="fileInfo" class="hidden mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-blue-800 dark:text-blue-200" id="fileName"></span>
                            <span class="text-xs text-blue-600 dark:text-blue-400 ml-2" id="fileSize"></span>
                        </div>
                        <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div id="progressContainer" class="hidden mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" id="progressBar" style="width: 0%"></div>
                    </div>
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-1">Memproses file...</p>
                </div>
                
                <!-- Tombol Proses -->
                <button type="submit" id="processBtn" disabled class="w-full bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-cog mr-2"></i> Proses Watermark
                </button>
            </form>
        </div>
        
        <!-- Format Options -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 text-center cursor-pointer" onclick="setFormat('pdf')">
                <i class="fas fa-file-pdf text-3xl text-red-500 mb-2"></i>
                <div class="font-medium text-gray-800 dark:text-white">PDF</div>
                <small class="text-gray-600 dark:text-gray-400">Dokumen PDF</small>
            </div>
            <div class="border-2 border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20" onclick="setFormat('docx')">
                <i class="fas fa-file-word text-3xl text-blue-500 mb-2"></i>
                <div class="font-medium text-gray-800 dark:text-white">DOCX</div>
                <small class="text-gray-600 dark:text-gray-400">Dokumen Word</small>
            </div>
        </div>
        
        <!-- Peringatan -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
            <h6 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i> Catatan Penting:
            </h6>
            <p class="text-sm text-yellow-700 dark:text-yellow-300">Beberapa file PDF dengan kompresi tertentu mungkin tidak dapat diproses sempurna. Jika mengalami kesalahan, coba gunakan PDF lain atau konversi terlebih dahulu ke format yang lebih kompatibel.</p>
        </div>
        
        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <h6 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">
                <i class="fas fa-info-circle mr-2"></i> Informasi:
            </h6>
            <p class="text-sm text-blue-700 dark:text-blue-300">Sistem ini menggunakan beberapa metode untuk memproses PDF. Jika metode utama gagal, sistem akan mencoba metode alternatif untuk memastikan watermark dapat ditambahkan.</p>
        </div>
        
        <!-- Panduan Penggunaan -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <h6 class="font-semibold text-gray-800 dark:text-white mb-3">
                <i class="fas fa-info-circle mr-2"></i> Cara Penggunaan:
            </h6>
            <ol class="text-sm text-gray-700 dark:text-gray-300 space-y-2 list-decimal list-inside">
                <li>Pilih file PDF atau DOCX yang akan ditambahkan watermark</li>
                <li>Klik tombol "Proses Watermark"</li>
                <li>Tunggu proses selesai</li>
                <li>Download hasil atau preview dokumen</li>
            </ol>
        </div>
        
        <!-- Pesan status -->
        @if(session('success'))
            <div class="mt-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span class="text-green-700 dark:text-green-300">{{ session('success') }}</span>
                </div>
                @if(session('downloadLink'))
                    <div class="flex gap-2 mt-3">
                        <a href="{{ session('downloadLink') }}" download="{{ session('fileName') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-download mr-2"></i> Download Hasil Watermark
                        </a>
                        <a href="{{ route('watermark.logs.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-list mr-2"></i> Lihat Log Watermark
                        </a>
                    </div>
                @endif
            </div>
        @endif
        
        @if(session('error'))
            <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    <span class="text-red-700 dark:text-red-300">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        
        <!-- Preview -->
        @if(session('downloadLink'))
            <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h6 class="font-semibold text-gray-800 dark:text-white mb-3">
                    <i class="fas fa-eye mr-2"></i> Preview Hasil Watermark:
                </h6>
                <div class="text-center mb-3">
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ session('fileName') }}</span>
                </div>
                <div class="w-full h-96 border border-gray-300 dark:border-gray-600 rounded">
                    <iframe src="{{ session('downloadLink') }}" class="w-full h-full rounded"></iframe>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Notification Container -->
<div id="notification" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <i class="fas fa-check-circle mr-2"></i>
    <span id="notificationText"></span>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('file');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const uploadForm = document.getElementById('uploadForm');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const processBtn = document.getElementById('processBtn');
    const notification = document.getElementById('notification');
    const notificationText = document.getElementById('notificationText');
    
    let selectedFile = null; // Tambahkan variabel untuk melacak file yang dipilih
    
    // File upload via click
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    });
    
    uploadArea.addEventListener('dragleave', function() {
        uploadArea.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect(e.dataTransfer.files[0]);
        }
    });
    
    // File input change
    fileInput.addEventListener('change', function() {
        // Reset state sebelum menangani file baru
        resetFormState();
        
        if (fileInput.files.length) {
            handleFileSelect(fileInput.files[0]);
        }
    });
    
    // Fungsi untuk reset form state
    function resetFormState() {
        selectedFile = null;
        fileInfo.classList.add('hidden');
        progressContainer.classList.add('hidden');
        progressBar.style.width = '0%';
        processBtn.disabled = true;
        processBtn.innerHTML = '<i class="fas fa-cog mr-2"></i> Proses Watermark';
        
        // Reset upload area
        uploadArea.innerHTML = `
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Klik atau tarik file ke sini</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Format yang didukung: PDF, DOCX (Maks. 10MB)</p>
            </div>
            <input type="file" name="file" id="file" accept=".pdf,.docx" required class="hidden">
        `;
        
        // Re-attach event listener to new file input
        const newFileInput = document.getElementById('file');
        newFileInput.addEventListener('change', function() {
            resetFormState();
            if (newFileInput.files.length) {
                handleFileSelect(newFileInput.files[0]);
            }
        });
    }
    
    // Handle file selection
    function handleFileSelect(file) {
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            showNotification('Ukuran file terlalu besar. Maksimal 10MB.', 'error');
            resetFormState();
            return;
        }
        
        // Check file type
        const validTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!validTypes.includes(file.type)) {
            showNotification('Format file tidak didukung. Hanya PDF dan DOCX.', 'error');
            resetFormState();
            return;
        }
        
        // Simpan file yang dipilih
        selectedFile = file;
        
        // Display file info
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.remove('hidden');
        
        // Update upload area
        uploadArea.innerHTML = `
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <i class="fas fa-file-${file.type === 'application/pdf' ? 'pdf' : 'word'} text-3xl text-green-500 mb-2"></i>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">${file.name}</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Format: ${file.type === 'application/pdf' ? 'PDF' : 'DOCX'}, Ukuran: ${formatFileSize(file.size)}</p>
            </div>
            <input type="file" name="file" id="file" accept=".pdf,.docx" required class="hidden">
        `;
        
        // Re-attach event listener to new file input
        const newFileInput = document.getElementById('file');
        newFileInput.addEventListener('change', function() {
            resetFormState();
            if (newFileInput.files.length) {
                handleFileSelect(newFileInput.files[0]);
            }
        });
        
        // Enable process button
        processBtn.disabled = false;
        
        showNotification('File siap diproses', 'success');
    }
    
    // Clear file function
    window.clearFile = function() {
        fileInput.value = '';
        resetFormState();
    }
    
    // Set format
    window.setFormat = function(format) {
        const formatOptions = document.querySelectorAll('.grid > div');
        formatOptions.forEach(option => {
            option.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            option.classList.add('border-gray-300');
        });
        
        event.currentTarget.classList.remove('border-gray-300');
        event.currentTarget.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        
        // Update file input accept attribute
        const fileInput = document.getElementById('file');
        if (format === 'pdf') {
            fileInput.setAttribute('accept', '.pdf');
        } else if (format === 'docx') {
            fileInput.setAttribute('accept', '.docx');
        }
    }
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Show notification
    function showNotification(message, type = 'success') {
        notificationText.textContent = message;
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg transform transition-transform duration-300 z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        
        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
        }, 3000);
    }
    
    // Form submission
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Validasi file
        if (!selectedFile) {
            showNotification('Silakan pilih file terlebih dahulu', 'error');
            return;
        }
        
        // Show progress bar
        progressContainer.classList.remove('hidden');
        processBtn.disabled = true;
        processBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        
        // Buat FormData untuk upload
        const formData = new FormData();
        formData.append('file', selectedFile);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Kirim file dengan fetch
        fetch('{{ route("watermark.text.process") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('Watermark berhasil ditambahkan!', 'success');
                // Redirect atau tampilkan hasil
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memproses file', 'error');
        })
        .finally(() => {
            // Reset button state
            processBtn.disabled = false;
            processBtn.innerHTML = '<i class="fas fa-cog mr-2"></i> Proses Watermark';
            progressContainer.classList.add('hidden');
            progressBar.style.width = '0%';
        });
        
        // Simulate progress (sementara)
        let progress = 0;
        const interval = setInterval(function() {
            progress += 5;
            progressBar.style.width = progress + '%';
            
            if (progress >= 90) { // Berhenti di 90% menunggu response
                clearInterval(interval);
            }
        }, 200);
    });
});
</script>
@endpush