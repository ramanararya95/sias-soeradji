@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">
    
    <!-- Header Section -->
    <div class="bg-white shadow-sm rounded-2xl border border-gray-200 p-8 mb-8 text-center">
        <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2 tracking-tight">
            📝 Registrasi Surat Tugas untuk 1 atau 2 Orang
        </h1>
        <p class="text-gray-500 text-sm md:text-base">
            Buat dan kelola surat tugas perjalanan dinas Anda dengan mudah dan cepat
        </p>
        <div class="mt-4 h-1 w-20 bg-gradient-to-r from-blue-500 to-indigo-500 rounded mx-auto"></div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="container-surat">
            <!-- Template selector -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Pilih Jenis Surat Tugas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="template-option {{ (isset($formData) && $formData['jenis_surat'] == 1) || old('jenis_surat', 1) == 1 ? 'selected' : '' }}" 
                                 onclick="selectTemplate(1)">
                                <h5 class="font-semibold">Surat Tugas 1 Orang</h5>
                                <p class="text-muted">Format standar untuk penugasan satu orang</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="template-option {{ (isset($formData) && $formData['jenis_surat'] == 2) || old('jenis_surat') == 2 ? 'selected' : '' }}" 
                                 onclick="selectTemplate(2)">
                                <h5 class="font-semibold">Surat Tugas 2 Orang</h5>
                                <p class="text-muted">Format untuk penugasan dua orang sekaligus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-secondary" id="resetFormBtn">
                    <i class="fas fa-redo mr-2"></i> Reset Form
                </button>
                <button type="button" id="refreshDataBtn" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-sync-alt mr-2"></i> Refresh Data Pegawai
                </button>
                <button type="button" id="debugBtn" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-bug mr-2"></i> Debug Autocomplete
                </button>
                <small class="text-muted ms-2">Klik jika ada perubahan data pegawai terbaru</small>
            </div>

            <form action="{{ route('surat_tugas.preview') }}" method="POST" id="suratTugasForm">
                @csrf
                <input type="hidden" id="jenis_surat" name="jenis_surat" value="{{ isset($formData) ? $formData['jenis_surat'] : old('jenis_surat', 1) }}">

                <!-- Document info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Surat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_naskah" class="form-label">Jenis Naskah</label>
                                <input type="text" id="jenis_naskah" name="jenis_naskah" class="form-control" required
                                       placeholder="Contoh: Surat Dinas / Nota dinas / Surat Undangan"
                                       value="{{ isset($formData) ? $formData['jenis_naskah'] : old('jenis_naskah') }}">
                                @error('jenis_naskah')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pengirim" class="form-label">Pengirim</label>
                                <input type="text" id="pengirim" name="pengirim" class="form-control" required
                                       placeholder="Contoh: dilihat dari jabatan pengirim"
                                       value="{{ isset($formData) ? $formData['pengirim'] : old('pengirim') }}">
                                @error('pengirim')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nomor_pengirim" class="form-label">Nomor Pengirim</label>
                                <input type="text" id="nomor_pengirim" name="nomor_pengirim" class="form-control" required
                                       placeholder="Contoh: diisikan nomor pengirim / jika tidak ada diisi (-)"
                                       value="{{ isset($formData) ? $formData['nomor_pengirim'] : old('nomor_pengirim') }}">
                                @error('nomor_pengirim')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                                <input type="text" id="tanggal_surat" name="tanggal_surat" class="form-control" required
                                       placeholder="Contoh: diisikan tanggal lengkap (3 September 2025)"
                                       value="{{ isset($formData) ? $formData['tanggal_surat'] : old('tanggal_surat') }}">
                                @error('tanggal_surat')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="hal" class="form-label">Hal</label>
                            <input type="text" id="hal" name="hal" class="form-control" required
                                   placeholder="Contoh: diisikan perihal surat"
                                   value="{{ isset($formData) ? $formData['hal'] : old('hal') }}">
                            @error('hal')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Person 1 -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Data Pegawai 1</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_gelar1" class="form-label">Nama (dengan gelar)</label>
                                <div class="position-relative">
                                    <input type="text" id="nama_gelar1" name="nama_gelar[]" class="form-control pegawai-autocomplete" required
                                           value="{{ isset($formData) ? $formData['nama_gelar'][0] : old('nama_gelar.0') }}"
                                           data-index="0">
                                    <div class="autocomplete-loading">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                                <small class="text-muted">Ketik minimal 2 karakter untuk mencari pegawai</small>
                                @error('nama_gelar.0')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nip1" class="form-label">NIP</label>
                                <input type="text" id="nip1" name="nip[]" class="form-control" required
                                       value="{{ isset($formData) ? $formData['nip'][0] : old('nip.0') }}"
                                       readonly>
                                @error('nip.0')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pangkat_golongan1" class="form-label">Pangkat/Golongan</label>
                                <input type="text" id="pangkat_golongan1" name="pangkat_golongan[]" class="form-control"
                                       value="{{ isset($formData) ? $formData['pangkat_golongan'][0] : old('pangkat_golongan.0') }}"
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jabatan1" class="form-label">Jabatan</label>
                                <input type="text" id="jabatan1" name="jabatan[]" class="form-control" 
                                       value="{{ isset($formData) ? $formData['jabatan'][0] : old('jabatan.0') }}"
                                       readonly>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Person 2 (conditionally shown) -->
                <div id="person2Fields" class="card mb-4" style="display: {{ (isset($formData) && $formData['jenis_surat'] == 2) || old('jenis_surat', 1) == 2 ? 'block' : 'none' }};">
                    <div class="card-header">
                        <h5 class="mb-0">Data Pegawai 2</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_gelar2" class="form-label">Nama (dengan gelar)</label>
                                <div class="position-relative">
                                    <input type="text" id="nama_gelar2" name="nama_gelar[]" class="form-control pegawai-autocomplete" 
                                           value="{{ isset($formData) ? $formData['nama_gelar'][1] : old('nama_gelar.1') }}"
                                           data-index="1">
                                    <div class="autocomplete-loading">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                                <small class="text-muted">Ketik minimal 2 karakter untuk mencari pegawai</small>
                                @error('nama_gelar.1')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nip2" class="form-label">NIP</label>
                                <input type="text" id="nip2" name="nip[]" class="form-control" 
                                       value="{{ isset($formData) ? $formData['nip'][1] : old('nip.1') }}"
                                       readonly>
                                @error('nip.1')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pangkat_golongan2" class="form-label">Pangkat/Golongan</label>
                                <input type="text" id="pangkat_golongan2" name="pangkat_golongan[]" class="form-control"
                                       value="{{ isset($formData) ? $formData['pangkat_golongan'][1] : old('pangkat_golongan.1') }}"
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jabatan2" class="form-label">Jabatan</label>
                                <input type="text" id="jabatan2" name="jabatan[]" class="form-control"
                                       value="{{ isset($formData) ? $formData['jabatan'][1] : old('jabatan.1') }}"
                                       readonly>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Task details -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Tugas</h5>
                        <button type="button" class="btn btn-success" id="addTaskBtn">
                            <i class="fas fa-plus mr-1"></i> Tambah Tugas
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless" id="taskTable">
                            <tbody>
                                @php
                                    $defaultTasks = [
                                        1 => 'mengikuti kegiatan dst',
                                        2 => 'melaksanakan tugas dengan penuh tanggung jawab',
                                        3 => 'melaporkan hasil kegiatan secara tertulis kepada atasan',
                                        4 => 'surat tugas ini berlaku dari tanggal '.date('d F Y'),
                                        5 => 'tidak wajib melakukan rekam kehadiran datang dan pulang',
                                        6 => 'biaya yang timbul dari Surat Tugas ini tidak dibebankan pada DIPA RSUP dr. Soeradji Tirtonegoro Klaten Tahun Anggaran '.date('Y')
                                    ];
                                    
                                    // Gunakan task count dari formData jika ada, atau dari old, atau default 6
                                    $taskCount = isset($formData) ? $formData['task_count'] : old('task_count', 6);
                                @endphp
                                
                                @for ($i = 1; $i <= $taskCount; $i++)
                                    <tr class="task-row">
                                        <td width="5%" class="align-top pt-3">{{ $i }}.</td>
                                        <td>
                                            <div class="form-group mb-0">
                                                <textarea id="untuk_{{ $i }}" name="untuk_{{ $i }}" class="form-control" rows="2" required>{{ isset($formData) ? $formData["untuk_$i"] : old("untuk_$i", $defaultTasks[$i] ?? '') }}</textarea>
                                            </div>
                                            @if ($i <= 6)
                                                <small class="text-muted">Contoh: {{ $i }}. {{ $defaultTasks[$i] }}</small>
                                            @endif
                                        </td>
                                        <td width="5%" class="align-middle">
                                            @if ($i > 6)
                                                <button type="button" class="btn btn-sm btn-danger remove-task-btn">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                        <input type="hidden" id="taskCount" name="task_count" value="{{ $taskCount }}">
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-eye mr-2"></i> Preview Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Debug Modal -->
<div class="modal fade" id="debugModal" tabindex="-1" aria-labelledby="debugModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="debugModalLabel">Debug Autocomplete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6>Informasi File Excel:</h6>
                    <div id="debugFileInfo" class="alert alert-info">Memeriksa...</div>
                </div>
                <div class="mb-3">
                    <h6>Hasil Pencarian Terakhir:</h6>
                    <div id="debugSearchResult" class="alert alert-secondary">Belum ada pencarian</div>
                </div>
                <div class="mb-3">
                    <h6>Sample Data Pegawai:</h6>
                    <div id="debugSampleData" class="alert alert-secondary">Memuat...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    [x-cloak] { display: none !important; }
    
    /* Modern Form Styles */
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        color: #4a5568;
    }
    
    .form-surat-tugas { 
        padding: 20px; 
    }
    
    .container-surat { 
        max-width: 1200px; 
        background: white; 
        border-radius: 16px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
        padding: 40px; 
        margin: 0 auto;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card:hover {
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 16px 24px;
    }
    
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 15px;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.2s ease;
        border: none;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }
    
    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }
    
    .btn-secondary:hover {
        background: #cbd5e0;
        transform: translateY(-2px);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(72, 187, 120, 0.3);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        color: white;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(245, 101, 101, 0.3);
    }
    
    /* Template selector styles */
    .template-option { 
        border: 2px solid #e2e8f0; 
        padding: 20px; 
        margin-bottom: 15px; 
        border-radius: 12px; 
        cursor: pointer; 
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .template-option:hover { 
        border-color: #667eea; 
        background-color: #f7fafc; 
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    .template-option.selected { 
        border-color: #667eea; 
        background-color: #edf2ff;
    }
    
    /* Style untuk autocomplete dropdown */
    .ui-autocomplete {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 9999 !important;
        background-color: white;
        border-radius: 8px;
    }

    .ui-menu-item {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .ui-menu-item:hover {
        background-color: #f7fafc;
    }

    .ui-state-active, .ui-widget-content .ui-state-active {
        background-color: #667eea !important;
        color: white !important;
        border: none !important;
        margin: 0 !important;
    }

    /* Loading spinner untuk autocomplete */
    .autocomplete-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
        color: #667eea;
    }

    .pegawai-autocomplete.ui-autocomplete-loading + .autocomplete-loading {
        display: block;
    }

    /* Task management styles */
    .task-row {
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .remove-task-btn {
        transition: all 0.2s ease;
    }
    .remove-task-btn:hover {
        transform: scale(1.1);
    }
    
    /* Validation styles */
    .form-control.is-invalid {
        border-color: #f56565;
    }
    .form-control.is-invalid:focus {
        border-color: #f56565;
        box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.25);
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
    
    /* Error message */
    .error-message {
        color: #f56565;
        font-size: 0.875rem;
        margin-top: 5px;
    }
    
    /* Task table styles */
    .task-table {
        width: 100%;
    }
    .task-table td {
        vertical-align: top;
        padding: 5px;
    }
    .task-number {
        width: 30px;
        font-weight: bold;
    }
    .task-content {
        width: 100%;
    }
    .task-action {
        width: 40px;
        text-align: center;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .container-surat {
            padding: 20px;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .card {
            margin-bottom: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
// Template selection
function selectTemplate(type) {
    document.getElementById("jenis_surat").value = type;
    document.getElementById("person2Fields").style.display = type === 2 ? "block" : "none";
    
    // Update required fields
    const fields = ["nama_gelar2", "nip2"];
    fields.forEach(field => {
        const el = document.getElementById(field);
        if (el) el.required = type === 2;
    });
    
    // Update UI selection
    document.querySelectorAll(".template-option").forEach(opt => {
        opt.classList.remove("selected");
    });
    
    // Select the correct option
    const options = document.querySelectorAll(".template-option");
    if (type === 1) {
        options[0].classList.add("selected");
    } else {
        options[1].classList.add("selected");
    }
}

// Loading functions
function showLoading() {
    document.getElementById('loadingOverlay').classList.add('active');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.remove('active');
}

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = 'notification ' + type;
    notification.classList.add('show');
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Form submission
document.getElementById('suratTugasForm').addEventListener('submit', function() {
    showLoading();
});

// Task management
document.addEventListener('DOMContentLoaded', function() {
    // Add new task
    document.getElementById('addTaskBtn').addEventListener('click', function() {
        const taskCount = parseInt(document.getElementById('taskCount').value) + 1;
        const taskTable = document.getElementById('taskTable').getElementsByTagName('tbody')[0];
        
        const newRow = document.createElement('tr');
        newRow.className = 'task-row';
        newRow.innerHTML = `
            <td width="5%" class="align-top pt-3">${taskCount}.</td>
            <td>
                <div class="form-group mb-0">
                    <textarea id="untuk_${taskCount}" name="untuk_${taskCount}" class="form-control" rows="2" required></textarea>
                </div>
            </td>
            <td width="5%" class="align-middle">
                <button type="button" class="btn btn-sm btn-danger remove-task-btn">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        
        taskTable.appendChild(newRow);
        document.getElementById('taskCount').value = taskCount;
        
        // Add event listener to new remove button
        newRow.querySelector('.remove-task-btn').addEventListener('click', function() {
            removeTask(this);
        });
        
        // Add validation to new textarea
        const newTextarea = newRow.querySelector('textarea');
        newTextarea.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        newTextarea.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Remove task
    function removeTask(button) {
        const row = button.closest('tr');
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';
        
        setTimeout(() => {
            row.remove();
            
            // Re-number remaining tasks
            const rows = document.querySelectorAll('#taskTable tbody tr.task-row');
            rows.forEach((row, index) => {
                const newNum = index + 1;
                row.cells[0].textContent = newNum + '.';
                
                // Update name and id of textarea
                const textarea = row.querySelector('textarea');
                textarea.name = 'untuk_' + newNum;
                textarea.id = 'untuk_' + newNum;
            });
            
            document.getElementById('taskCount').value = rows.length;
        }, 300);
    }
    
    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-task-btn').forEach(button => {
        button.addEventListener('click', function() {
            removeTask(this);
        });
    });

    // Autocomplete untuk pegawai
    $('.pegawai-autocomplete').each(function() {
        var index = $(this).data('index');
        var $input = $(this);
        
        $input.autocomplete({
            source: function(request, response) {
                // Debug: Log request
                console.log('Autocomplete request:', request);
                
                $.ajax({
                    url: '{{ route("surat_tugas.search_pegawai") }}',
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    beforeSend: function() {
                        // Tampilkan loading
                        $input.addClass('ui-autocomplete-loading');
                    },
                    success: function(data) {
                        // Debug: Log response
                        console.log('Autocomplete response:', data);
                        
                        // Sembunyikan loading
                        $input.removeClass('ui-autocomplete-loading');
                        
                        response(data);
                    },
                    error: function(xhr, status, error) {
                        // Debug: Log error
                        console.error('Autocomplete error:', error);
                        console.error('Response text:', xhr.responseText);
                        
                        // Sembunyikan loading
                        $input.removeClass('ui-autocomplete-loading');
                        
                        showNotification('Error retrieving data.', 'error');
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $input.val(ui.item.label);
                
                // Isi field terkait
                var nipFieldId = index === 0 ? 'nip1' : 'nip2';
                var pangkatFieldId = index === 0 ? 'pangkat_golongan1' : 'pangkat_golongan2';
                var jabatanFieldId = index === 0 ? 'jabatan1' : 'jabatan2';
                
                $('#' + nipFieldId).val(ui.item.nip);
                $('#' + pangkatFieldId).val(ui.item.pangkat_golongan);
                $('#' + jabatanFieldId).val(ui.item.jabatan);
                
                // Tambahkan efek visual
                $('#' + nipFieldId + ', #' + pangkatFieldId + ', #' + jabatanFieldId).addClass('bg-light');
                setTimeout(function() {
                    $('#' + nipFieldId + ', #' + pangkatFieldId + ', #' + jabatanFieldId).removeClass('bg-light');
                }, 1000);
                
                return false; // Mencegah default behavior
            },
            focus: function(event, ui) {
                // Saat focus (bukan select), jangan ubah nilai input
                return false;
            },
            open: function() {
                // Debug: Log saat dropdown dibuka
                console.log('Autocomplete dropdown opened');
            },
            close: function() {
                // Debug: Log saat dropdown ditutup
                console.log('Autocomplete dropdown closed');
            }
        });
    });

    // Reset form functionality
    document.getElementById('resetFormBtn').addEventListener('click', function() {
        // Reset form values
        document.getElementById('suratTugasForm').reset();
        
        // Reset template selection to default
        selectTemplate(1);
        
        // Reset task count to default
        document.getElementById('taskCount').value = 6;
        
        // Remove any additional task rows beyond the default 6
        const taskRows = document.querySelectorAll('#taskTable tbody tr.task-row');
        taskRows.forEach((row, index) => {
            if (index >= 6) {
                row.remove();
            }
        });
        
        // Clear any validation errors
        document.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid');
        });
        
        // Show notification
        showNotification('Form telah direset', 'success');
    });

    // Form validation
    document.getElementById('suratTugasForm').addEventListener('submit', function(e) {
        // Check if all required fields are filled
        const requiredFields = document.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            hideLoading();
            showNotification('Silakan lengkapi semua field yang diperlukan', 'error');
        }
    });

    // Initialize template selection based on current value
    const currentTemplate = document.getElementById('jenis_surat').value;
    selectTemplate(parseInt(currentTemplate));

    // Refresh data pegawai
    document.getElementById('refreshDataBtn').addEventListener('click', function() {
        showLoading();
        
        // Kirim request untuk refresh data
        fetch('{{ route("surat_tugas.search_pegawai") }}?refresh=true')
            .then(response => response.json())
            .then(data => {
                hideLoading();
                showNotification('Data pegawai berhasil diperbarui', 'success');
                
                // Kosongkan field autocomplete
                $('.pegawai-autocomplete').val('');
                $('#nip1, #nip2, #pangkat_golongan1, #pangkat_golongan2, #jabatan1, #jabatan2').val('');
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showNotification('Gagal memperbarui data pegawai', 'error');
            });
    });

    // Debug functionality
    document.getElementById('debugBtn').addEventListener('click', function() {
        const debugModal = new bootstrap.Modal(document.getElementById('debugModal'));
        debugModal.show();
        
        // Check file info
        fetch('{{ route("surat_tugas.search_pegawai") }}?debug=file')
            .then(response => response.json())
            .then(data => {
                const fileInfo = document.getElementById('debugFileInfo');
                if (data.file_exists) {
                    fileInfo.innerHTML = `
                        <strong>File ditemukan:</strong> ${data.file_path}<br>
                        <strong>Ukuran file:</strong> ${data.file_size} bytes<br>
                        <strong>Terakhir dimodifikasi:</strong> ${data.last_modified}
                    `;
                } else {
                    fileInfo.innerHTML = `<strong>File tidak ditemukan:</strong> ${data.file_path}`;
                    fileInfo.className = 'alert alert-danger';
                }
            })
            .catch(error => {
                document.getElementById('debugFileInfo').innerHTML = `Error: ${error.message}`;
                document.getElementById('debugFileInfo').className = 'alert alert-danger';
            });
        
        // Get sample data
        fetch('{{ route("surat_tugas.search_pegawai") }}?debug=sample')
            .then(response => response.json())
            .then(data => {
                const sampleData = document.getElementById('debugSampleData');
                if (data.length > 0) {
                    let html = '<table class="table table-sm"><thead><tr><th>Nama</th><th>NIP</th><th>Jabatan</th></tr></thead><tbody>';
                    data.slice(0, 5).forEach(pegawai => {
                        html += `<tr><td>${pegawai.nama}</td><td>${pegawai.nip}</td><td>${pegawai.jabatan}</td></tr>`;
                    });
                    html += '</tbody></table>';
                    html += `<p class="mt-2"><strong>Total data:</strong> ${data.total} pegawai</p>`;
                    sampleData.innerHTML = html;
                } else {
                    sampleData.innerHTML = 'Tidak ada data pegawai';
                    sampleData.className = 'alert alert-warning';
                }
            })
            .catch(error => {
                document.getElementById('debugSampleData').innerHTML = `Error: ${error.message}`;
                document.getElementById('debugSampleData').className = 'alert alert-danger';
            });
    });
});
</script>
@endpush