@extends('layouts.app')

@section('styles')
<style>
    .document-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .document-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .document-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }
    
    .document-actions {
        display: flex;
        gap: 10px;
    }
    
    .document-content {
        min-height: 500px;
        padding: 20px;
        border: 1px solid #eee;
        background-color: #fff;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-size: 14px;
    }
    
    .btn-primary {
        background-color: #4361ee;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #3f37c9;
    }
    
    .btn-success {
        background-color: #06ffa5;
        color: #333;
    }
    
    .btn-success:hover {
        background-color: #00e8a0;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="document-container">
        <div class="document-header">
            <div class="document-title">
                {{ $surat->filename_word }}
            </div>
            <div class="document-actions">
                <a href="{{ route('log_surat_tugas.download', $surat->id) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Download
                </a>
                <a href="{{ route('log_surat_tugas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="document-content">
            {!! $htmlContent !!}
        </div>
    </div>
</div>
@endsection