<!-- resources/views/laporan/arsip/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arsip {{ ucfirst($jenisArsip) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
            border-collapse: collapse;
        }
        .info td {
            padding: 5px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th, table.data td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table.data th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
            float: right;
            text-align: center;
        }
        .signature p {
            margin: 5px 0;
        }
        .signature .line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin: 50px 0 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ARSIP {{ strtoupper($jenisArsip) }}</h1>
        <p>SISTEM INFORMASI ARSIP SOERADJI</p>
    </div>
    
    <div class="info">
        <table>
            <tr>
                <td width="150">Tanggal Cetak</td>
                <td>: {{ date('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Dicetak Oleh</td>
                <td>: {{ $user->nama_lengkap }}</td>
            </tr>
            @if($tanggalMulai)
            <tr>
                <td>Periode</td>
                <td>: {{ date('d/m/Y', strtotime($tanggalMulai)) }} - {{ date('d/m/Y', strtotime($tanggalSelesai)) }}</td>
            </tr>
            @endif
            @if($status)
            <tr>
                <td>Status</td>
                <td>: {{ ucfirst($status) }}</td>
            </tr>
            @endif
        </table>
    </div>
    
    <table class="data">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="100">Nomor Arsip</th>
                <th>Judul/Nama</th>
                <th width="80">Tanggal</th>
                <th width="120">Pembuat</th>
                
                @if($jenisArsip === 'aktif' || $jenisArsip === 'inaktif' || $jenisArsip === 'vital')
                <th width="100">Kurun Waktu</th>
                <th width="120">Tingkat Perkembangan</th>
                <th width="50">Jumlah</th>
                <th width="100">Lokasi Simpan</th>
                <th width="100">Deskripsi Fisik</th>
                <th width="80">Media Arsip</th>
                @elseif($jenisArsip === 'alihmedia')
                <th width="100">Media Asal</th>
                <th width="100">Media Tujuan</th>
                <th width="50">Jumlah</th>
                <th width="100">Lokasi Simpan</th>
                <th width="100">Deskripsi Fisik</th>
                @endif
                
                <th width="70">Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($arsip as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nomor ?? '-' }}</td>
                <td>{{ $item->judul ?? $item->nama ?? '-' }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>{{ $item->user->nama_lengkap }}</td>
                
                @if($jenisArsip === 'aktif' || $jenisArsip === 'inaktif' || $jenisArsip === 'vital')
                <td>{{ $item->kurun_waktu ?? '-' }}</td>
                <td>{{ $item->tingkat_perkembangan ?? '-' }}</td>
                <td>{{ $item->jumlah ?? '-' }}</td>
                <td>{{ $item->lokasi_simpan ?? '-' }}</td>
                <td>{{ $item->deskripsi_fisik ?? '-' }}</td>
                <td>{{ $item->media_arsip ?? '-' }}</td>
                @elseif($jenisArsip === 'alihmedia')
                <td>{{ $item->media_asal ?? '-' }}</td>
                <td>{{ $item->media_tujuan ?? '-' }}</td>
                <td>{{ $item->jumlah ?? '-' }}</td>
                <td>{{ $item->lokasi_simpan ?? '-' }}</td>
                <td>{{ $item->deskripsi_fisik ?? '-' }}</td>
                @endif
                
                <td>{{ ucfirst($item->status ?? '-') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="text-align: center;">Tidak ada data arsip yang ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Total: {{ $arsip->count() }} data</p>
    </div>
    
    <div class="signature">
        <div class="line"></div>
        <p>{{ $user->nama_lengkap }}</p>
        <p>{{ ucfirst($user->role) }}</p>
    </div>
</body>
</html>