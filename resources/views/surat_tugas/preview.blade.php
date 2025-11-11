@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0 text-gray-800">Preview Surat Tugas</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview Surat Tugas</h6>
        </div>
        <div class="card-body">
            <div class="preview-wrapper" id="previewContent">
                {!! $previewHtml !!}
            </div>
            
            <div class="mt-4 d-flex justify-content-between flex-wrap">
                <div class="btn-group mb-2" role="group">
                    <button type="button" class="btn btn-primary" id="cetakBtn">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('surat_tugas.generate_word') }}" class="btn btn-success" id="generateWordBtn">
                        <i class="fas fa-file-word mr-2"></i> Generate Word
                    </a>
                </div>
                
                <div class="btn-group mb-2" role="group">
                    <a href="{{ route('surat_tugas.form') }}" class="btn btn-warning" id="editFormBtn">
                        <i class="fas fa-edit mr-2"></i> Edit Form
                    </a>
                    <form action="{{ route('surat_tugas.save') }}" method="POST" class="d-inline" id="saveToLogForm">
                        @csrf
                        <button type="submit" class="btn btn-info" id="saveToLogBtn">
                            <i class="fas fa-save mr-2"></i> Simpan ke Log
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- Notification container -->
<div id="notification" class="notification"></div>
@endsection

@push('styles')
<style>
    .preview-wrapper {
        border: 1px solid #ddd;
        padding: 20px;
        min-height: 500px;
        background-color: #fff;
        overflow: auto;
        max-height: 800px;
    }
    
    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s, visibility 0.3s;
    }
    .loading-overlay.active {
        visibility: visible;
        opacity: 1;
    }
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Notification styles */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        transform: translateX(150%);
        transition: transform 0.3s ease-out;
    }
    .notification.show {
        transform: translateX(0);
    }
    .notification.success {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    }
    .notification.error {
        background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    }
    .notification.info {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
 $(document).ready(function() {
    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        notification.textContent = message;
        notification.className = 'notification ' + type;
        notification.classList.add('show');
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
    
    // Fungsi untuk menampilkan loading
    function showLoading() {
        document.getElementById('loadingOverlay').classList.add('active');
    }
    
    function hideLoading() {
        document.getElementById('loadingOverlay').classList.remove('active');
    }
    
    // Tombol Cetak
    $('#cetakBtn').click(function() {
        showLoading();
        
        const element = document.getElementById('previewContent');
        const opt = {
            margin: 10,
            filename: 'Surat_Tugas_' + new Date().toISOString().slice(0,10) + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        // Generate PDF
        html2pdf().set(opt).from(element).save().then(() => {
            hideLoading();
            showNotification('PDF berhasil dibuat dan diunduh', 'success');
        }).catch(error => {
            hideLoading();
            showNotification('Gagal membuat PDF: ' + error.message, 'error');
            console.error('Error creating PDF:', error);
        });
    });
    
    // Tombol Generate Word
    $('#generateWordBtn').click(function(e) {
        e.preventDefault();
        showLoading();
        
        // Simulasi proses generate
        setTimeout(() => {
            hideLoading();
            // Redirect ke endpoint generate word
            window.location.href = $(this).attr('href');
        }, 1000);
    });
    
    // Tombol Edit Form
    $('#editFormBtn').click(function(e) {
        // Data sudah tersimpan di session, jadi langsung redirect
        window.location.href = $(this).attr('href');
    });
    
    // Tombol Simpan ke Log
    $('#saveToLogForm').submit(function(e) {
        e.preventDefault();
        showLoading();
        
        // Submit form via AJAX
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    showNotification(response.message, 'success');
                    
                    // Redirect ke halaman log setelah 2 detik
                    setTimeout(() => {
                        window.location.href = '{{ route("log_surat_tugas.index") }}';
                    }, 2000);
                } else {
                    showNotification(response.message, 'error');
                }
            },
            error: function(xhr) {
                hideLoading();
                
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        const errorData = JSON.parse(xhr.responseText);
                        if (errorData.message) {
                            errorMessage = errorData.message;
                        }
                    } catch (e) {
                        // Jika tidak bisa parse JSON, gunakan response text
                        errorMessage = xhr.responseText.substring(0, 100);
                    }
                }
                
                showNotification(errorMessage, 'error');
                console.error('Error saving to log:', xhr);
            }
        });
    });
});
</script>
@endpush