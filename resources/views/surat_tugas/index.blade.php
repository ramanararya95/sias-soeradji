@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Log Surat Tugas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('surat_tugas.form') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Surat Tugas Baru
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama File</th>
                <th>Nama Pegawai</th>
                <th>Hal</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($suratTugasList as $index => $log)
            <tr>
                <td>{{ $suratTugasList->firstItem() + $index }}</td>
                <td>{{ $log->filename_word }}</td>
                <td>{{ $log->nama_gelar1 }} {{ $log->nama_gelar2 ? ', ' . $log->nama_gelar2 : '' }}</td>
                <td>{{ $log->hal }}</td>
                <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    @if($log->filename_word)
                        <a href="{{ route('log_surat_tugas.download', $log->id) }}" class="btn btn-sm btn-success">Download</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $suratTugasList->links() }}
</div>
@endsection