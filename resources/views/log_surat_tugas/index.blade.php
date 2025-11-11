@extends('layouts.app')

@section('styles')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #06ffa5;
        --danger-color: #ff006e;
        --warning-color: #ffbe0b;
        --info-color: #00b4d8;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --muted-color: #6c757d;
        --border-radius: 12px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }
    
    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        color: #333;
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 30px;
        border-radius: var(--border-radius);
        margin-bottom: 30px;
        box-shadow: var(--box-shadow);
        position: relative;
        z-index: 1;
    }
    
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stats-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 25px;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-color);
    }
    
    .stats-card.success::before { background: var(--success-color); }
    .stats-card.warning::before { background: var(--warning-color); }
    .stats-card.info::before { background: var(--info-color); }
    
    .stats-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .stats-title { font-size: 14px; font-weight: 500; color: var(--muted-color); margin: 0; }
    .stats-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .stats-icon.primary { background-color: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
    .stats-icon.success { background-color: rgba(6, 255, 165, 0.1); color: var(--success-color); }
    .stats-icon.warning { background-color: rgba(255, 190, 11, 0.1); color: var(--warning-color); }
    .stats-icon.info { background-color: rgba(0, 180, 216, 0.1); color: var(--info-color); }
    
    .stats-value { font-size: 32px; font-weight: 700; color: var(--dark-color); margin: 0; }
    .stats-change { display: flex; align-items: center; gap: 5px; font-size: 12px; margin-top: 10px; }
    .stats-change.positive { color: #28a745; }
    .stats-change.negative { color: #dc3545; }
    .stats-change.neutral { color: var(--muted-color); }
    
    .card {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        margin-bottom: 30px;
        border: none;
        transition: var(--transition);
    }
    
    .card-header {
        padding: 20px 25px;
        border-bottom: 1px solid #e9ecef;
        background-color: var(--light-color);
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .card-body { padding: 25px; }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: var(--transition);
        border: none;
        font-size: 14px;
    }
    
    .btn-primary { background-color: var(--primary-color); color: white; }
    .btn-primary:hover { background-color: var(--secondary-color); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3); }
    .btn-danger { background-color: var(--danger-color); color: white; }
    .btn-danger:hover { background-color: #d5005f; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 0, 110, 0.3); }
    .btn-success { background-color: var(--success-color); color: var(--dark-color); }
    .btn-success:hover { background-color: #00e8a0; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(6, 255, 165, 0.3); }
    .btn-warning { background-color: var(--warning-color); color: var(--dark-color); }
    .btn-warning:hover { background-color: #ffaa00; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 190, 11, 0.3); }
    .btn-info { background-color: var(--info-color); color: white; }
    .btn-info:hover { background-color: #0099c7; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 180, 216, 0.3); }
    .btn-outline-primary { background-color: transparent; color: var(--primary-color); border: 1px solid var(--primary-color); }
    .btn-outline-primary:hover { background-color: var(--primary-color); color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3); }
    .btn-sm { padding: 6px 12px; font-size: 12px; }
    
    /* --- PERBAIKAN UTAMA UNTUK TABEL --- */
    .table-responsive {
        overflow-x: auto; /* Fallback, tapi seharusnya tidak perlu scroll */
    }

    .table-custom {
        width: 100%;
        min-width: 800px; /* Minimal lebar agar tidak terlalu sempit di layar besar */
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-custom th, .table-custom td {
        padding: 12px 8px; /* Kurangi padding horizontal */
        text-align: left;
        vertical-align: top; /* Sejajarkan ke atas untuk teks yang wrap */
        border-bottom: 1px solid #e9ecef;
    }
    
    .table-custom th {
        background-color: var(--light-color);
        font-weight: 600;
        color: var(--dark-color);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap; /* Header tidak boleh wrap */
    }
    
    .table-custom tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }

    /* Kolom khusus */
    .table-custom .col-no { width: 5%; text-align: center; }
    .table-custom .col-file { width: 25%; }
    .table-custom .col-pegawai { width: 25%; }
    .table-custom .col-kegiatan { width: 25%; }
    .table-custom .col-tanggal { width: 10%; white-space: nowrap; }
    .table-custom .col-aksi { width: 10%; text-align: center; }

    /* Style untuk nama file yang panjang */
    .filename-link {
        display: block;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--dark-color);
        font-weight: 500;
        text-decoration: none;
    }
    .filename-link:hover {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    /* Style untuk info file di bawah nama file */
    .file-meta {
        margin-top: 4px;
        font-size: 11px;
        color: var(--muted-color);
    }
    
    .action-cell {
        display: flex;
        gap: 5px;
        justify-content: center;
        flex-wrap: wrap; /* Biarkan tombol pindah baris jika sempit */
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        font-size: 14px;
    }
    
    /* --- RESPONSIVE UNTUK MOBILE --- */
    @media screen and (max-width: 992px) {
        .table-custom .col-pegawai, .table-custom .col-kegiatan {
            font-size: 13px;
        }
    }

    @media screen and (max-width: 768px) {
        /* Sembunyikan tabel asli */
        .table-responsive thead {
            display: none;
        }
        .table-responsive, .table-custom tbody, .table-custom tr, .table-custom td {
            display: block;
            width: 100% !important;
        }
        
        .table-custom tr {
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            border-radius: var(--border-radius);
            padding: 15px;
            background: white;
            box-shadow: var(--box-shadow);
        }
        
        .table-custom td {
            border: none;
            padding: 8px 0;
            position: relative;
            padding-left: 35%; /* Beri ruang untuk label */
        }
        
        /* Tambahkan label pseudo-element untuk setiap sel */
        .table-custom td:before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            top: 8px;
            width: 30%;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 12px;
            text-transform: uppercase;
        }
        
        /* Reset style untuk tampilan kartu */
        .table-custom .col-no, .table-custom .col-file, .table-custom .col-pegawai, 
        .table-custom .col-kegiatan, .table-custom .col-tanggal, .table-custom .col-aksi {
            width: 100% !important;
            text-align: left !important;
        }
        
        .action-cell {
            justify-content: flex-start;
        }
    }

    /* Notifikasi dan style lainnya tetap sama */
    .notification-container { /* ... */ }
    .notification { /* ... */ }
    /* ... copy style notifikasi dari kode sebelumnya jika diperlukan ... */
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="page-header">
        <h1><i class="fas fa-briefcase"></i> Log Surat Tugas</h1>
        <p>Kelola dan pantau semua surat tugas yang telah dibuat</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stats-card">
            <div class="stats-header">
                <p class="stats-title">Total Surat Tugas</p>
                <div class="stats-icon primary"><i class="fas fa-file-alt"></i></div>
            </div>
            <p class="stats-value">{{ $total_count ?? 0 }}</p>
            <div class="stats-change positive"><i class="fas fa-arrow-up"></i><span>100% dari awal bulan</span></div>
        </div>
        <div class="stats-card success">
            <div class="stats-header">
                <p class="stats-title">Sudah Di-Generate</p>
                <div class="stats-icon success"><i class="fas fa-check-circle"></i></div>
            </div>
            <p class="stats-value">{{ $generated_count ?? 0 }}</p>
            <div class="stats-change positive"><i class="fas fa-arrow-up"></i><span>{{ $total_count > 0 ? round(($generated_count / $total_count) * 100) : '0' }}% dari total</span></div>
        </div>
        <div class="stats-card warning">
            <div class="stats-header">
                <p class="stats-title">Masih Draft</p>
                <div class="stats-icon warning"><i class="fas fa-edit"></i></div>
            </div>
            <p class="stats-value">{{ $draft_count ?? 0 }}</p>
            <div class="stats-change {{ $draft_count > 0 ? 'negative' : 'positive' }}">
                <i class="fas fa-arrow-{{ $draft_count > 0 ? 'down' : 'up' }}"></i>
                <span>{{ $total_count > 0 ? round(($draft_count / $total_count) * 100) : '0' }}% dari total</span>
            </div>
        </div>
        <div class="stats-card info">
            <div class="stats-header">
                <p class="stats-title">Generate Hari Ini</p>
                <div class="stats-icon info"><i class="fas fa-calendar-day"></i></div>
            </div>
            <p class="stats-value">{{ $today_count ?? 0 }}</p>
            <div class="stats-change neutral">
                <i class="fas fa-minus"></i>
                <span>sama dengan kemarin</span>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Surat Tugas</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('surat_tugas.form') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Tambah (1-2 Orang)
                </a>
                <a href="{{ route('surat_tugas.form_multiple') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-users"></i> Tambah (>2 Orang)
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-file">Nama File</th>
                            <th class="col-pegawai">Nama Pegawai</th>
                            <th class="col-kegiatan">Kegiatan</th>
                            <th class="col-tanggal">Tanggal</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratTugasList as $index => $surat)
                        <tr>
                            <td data-label="No" class="col-no">{{ $index + 1 }}</td>
                            <td data-label="Nama File" class="col-file">
                                @if($surat->filename_word)
                                    <a href="#" class="filename-link" onclick="editFilename({{ $surat->id }}, '{{ $surat->filename_word }}')" title="{{ $surat->filename_word }}">
                                        {{ $surat->filename_word }}
                                    </a>
                                    <div class="file-meta">
                                        <span class="badge bg-secondary">{{ $surat->template_type == 1 ? '1 Orang' : ($surat->template_type == 2 ? '2 Orang' : 'Banyak') }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Belum di-generate</span>
                                @endif
                            </td>
                            <td data-label="Nama Pegawai" class="col-pegawai">
                                {{ $surat->nama_gelar1 }}
                                @if($surat->nama_gelar2)
                                    <br>{{ $surat->nama_gelar2 }}
                                @endif
                                @if($surat->template_type == 3)
                                    <br><span class="text-muted">dkk.</span>
                                @endif
                            </td>
                            <td data-label="Kegiatan" class="col-kegiatan">{{ $surat->hal }}</td>
                            <td data-label="Tanggal" class="col-tanggal">{{ \Carbon\Carbon::parse($surat->created_at)->format('d M y') }}</td>
                            <td data-label="Aksi" class="col-aksi">
                                <div class="action-cell">
                                    @if($surat->filename_word)
                                        <a href="{{ route('log_surat_tugas.view', $surat->id) }}" class="btn btn-sm btn-info action-btn" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('log_surat_tugas.download', $surat->id) }}" class="btn btn-sm btn-success action-btn" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button class="btn btn-sm btn-warning action-btn" onclick="editFilename({{ $surat->id }}, '{{ $surat->filename_word }}')" title="Edit Nama File">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-primary action-btn" onclick="generateWord({{ $surat->id }})" title="Generate Word">
                                            <i class="fas fa-file-word"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-danger action-btn" onclick="confirmDelete({{ $surat->id }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada data surat tugas</h5>
                                <p class="text-muted">Belum ada surat tugas yang dibuat. Mulai dengan menambahkan surat tugas baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $suratTugasList->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Nama File -->
<div id="editFilenameModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Nama File</h4>
            <span class="close" onclick="closeEditModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editFilenameForm">
                <div class="form-group">
                    <label for="newFilename">Nama File Baru</label>
                    <input type="text" class="form-control" id="newFilename" name="newFilename" required>
                    <small class="form-text">Pastikan nama file diakhiri dengan .docx</small>
                </div>
                <input type="hidden" id="suratId" name="suratId">
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
            <button type="button" class="btn btn-primary" onclick="saveFilename()">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Konfirmasi Hapus</h4>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus surat tugas ini?</p>
            <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fungsi untuk edit nama file
    function editFilename(id, currentFilename) {
        document.getElementById('suratId').value = id;
        document.getElementById('newFilename').value = currentFilename;
        document.getElementById('editFilenameModal').style.display = 'block';
    }
    
    function closeEditModal() {
        document.getElementById('editFilenameModal').style.display = 'none';
    }
    
    function saveFilename() {
        const id = document.getElementById('suratId').value;
        const newFilename = document.getElementById('newFilename').value;
        
        fetch(`{{ route('log_surat_tugas.update_filename') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id: id, filename: newFilename })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Nama file berhasil diperbarui');
                window.location.reload();
            } else {
                alert(data.message || 'Gagal memperbarui nama file');
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function generateWord(id) {
        if (confirm('Generate file Word untuk surat tugas ini?')) {
            fetch(`{{ route('log_surat_tugas.generate') }}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('File Word berhasil dibuat');
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal membuat file Word');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    
    function confirmDelete(id) {
        document.getElementById('confirmDeleteBtn').setAttribute('data-id', id);
        document.getElementById('deleteModal').style.display = 'block';
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        fetch(`{{ route('log_surat_tugas.delete') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Surat tugas berhasil dihapus');
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menghapus surat tugas');
            }
        })
        .catch(error => console.error('Error:', error));
    });
    
    // Tutup modal jika klik di luar
    window.onclick = function(event) {
        const editModal = document.getElementById('editFilenameModal');
        const deleteModal = document.getElementById('deleteModal');
        if (event.target == editModal) { editModal.style.display = 'none'; }
        if (event.target == deleteModal) { deleteModal.style.display = 'none'; }
    }
</script>
@endsection