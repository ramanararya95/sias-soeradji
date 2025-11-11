@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Pindahkan semua CSS ke sini -->
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
        }
        
        .card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
            border: none;
        }
        
        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e9ecef;
            background-color: var(--light-color);
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .card-body {
            padding: 25px;
        }
        
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
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: var(--dark-color);
        }
        
        .btn-success:hover {
            background-color: #00e8a0;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(6, 255, 165, 0.3);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #d5005f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 0, 110, 0.3);
        }
        
        .btn-outline-primary {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-secondary {
            background-color: var(--muted-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
        }
        
        .form-control {
            display: block;
            width: 100%;
            padding: 12px 15px;
            font-size: 14px;
            line-height: 1.5;
            color: var(--dark-color);
            background-color: white;
            background-clip: padding-box;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            color: var(--dark-color);
            background-color: white;
            border-color: var(--primary-color);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        
        .pegawai-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background-color: var(--light-color);
            border-radius: 8px;
            border: 1px solid #e9ecef;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .pegawai-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .pegawai-input {
            flex-grow: 1;
        }
        
        .text-danger {
            color: var(--danger-color);
        }
    </style>
    
    <div class="page-header">
        <h1>
            <i class="fas fa-users"></i>
            Buat Surat Tugas (Banyak Pegawai)
        </h1>
        <p>Isi form di bawah untuk membuat surat tugas untuk lebih dari dua pegawai.</p>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Surat Tugas</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('surat_tugas.store_multiple') }}" method="POST">
                @csrf
                
                <!-- Hal -->
                <div class="form-group">
                    <label for="hal" class="form-label">Perihal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('hal') is-invalid @enderror" id="hal" name="hal" value="{{ old('hal') }}" placeholder="Misal: Pengambilan Sertifikat" required>
                    @error('hal')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Daftar Pegawai -->
                <div class="form-group">
                    <label class="form-label">Daftar Pegawai <span class="text-danger">*</span></label>
                    <div id="pegawai-container">
                        <!-- 3 pegawai awal -->
                        <div class="pegawai-item">
                            <div class="pegawai-number">1</div>
                            <div class="pegawai-input">
                                <input type="text" class="form-control" name="nama_pegawai[]" placeholder="Nama lengkap pegawai" required>
                            </div>
                        </div>
                        <div class="pegawai-item">
                            <div class="pegawai-number">2</div>
                            <div class="pegawai-input">
                                <input type="text" class="form-control" name="nama_pegawai[]" placeholder="Nama lengkap pegawai" required>
                            </div>
                        </div>
                        <div class="pegawai-item">
                            <div class="pegawai-number">3</div>
                            <div class="pegawai-input">
                                <input type="text" class="form-control" name="nama_pegawai[]" placeholder="Nama lengkap pegawai" required>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary" onclick="addPegawai()">
                        <i class="fas fa-plus"></i> Tambah Pegawai
                    </button>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('log_surat_tugas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Surat Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let pegawaiCount = 3;

    function addPegawai() {
        pegawaiCount++;
        
        const container = document.getElementById('pegawai-container');
        
        const pegawaiItem = document.createElement('div');
        pegawaiItem.className = 'pegawai-item';
        
        pegawaiItem.innerHTML = `
            <div class="pegawai-number">${pegawaiCount}</div>
            <div class="pegawai-input">
                <input type="text" class="form-control" name="nama_pegawai[]" placeholder="Nama lengkap pegawai" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removePegawai(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        
        container.appendChild(pegawaiItem);
    }
    
    function removePegawai(button) {
        // Hanya boleh hapus jika jumlah pegawai lebih dari 3
        if (pegawaiCount > 3) {
            button.parentElement.remove();
            updatePegawaiNumbers();
        } else {
            alert('Minimal harus ada 3 pegawai.');
        }
    }
    
    function updatePegawaiNumbers() {
        const items = document.querySelectorAll('.pegawai-item');
        pegawaiCount = items.length;
        
        items.forEach((item, index) => {
            const numberElement = item.querySelector('.pegawai-number');
            if (numberElement) {
                numberElement.textContent = index + 1;
            }
        });
    }
</script>
@endsection