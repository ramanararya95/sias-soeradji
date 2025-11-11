<!-- resources/views/notifications/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Notifikasi - SIAS Soeradji')

@section('header-title', 'Detail Notifikasi')
@section('header-subtitle', 'Detail Notifikasi Anda')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="mb-6">
        <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Notifikasi
        </a>
    </div>
    
    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                @if($notification->type == 'info')
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-info text-blue-500 text-lg"></i>
                </div>
                @elseif($notification->type == 'success')
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-500 text-lg"></i>
                </div>
                @elseif($notification->type == 'warning')
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation text-yellow-500 text-lg"></i>
                </div>
                @elseif($notification->type == 'error')
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-500 text-lg"></i>
                </div>
                @else
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-gray-500 text-lg"></i>
                </div>
                @endif
            </div>
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-2">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $notification->title }}
                    </h2>
                    @if(!$notification->read_at)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Baru
                    </span>
                    @endif
                </div>
                <div class="prose max-w-none dark:prose-invert">
                    <p class="text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                        {{ $notification->message }}
                    </p>
                </div>
                <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                    <span>
                        <i class="fas fa-clock mr-1"></i>
                        {{ $notification->created_at->format('d M Y H:i') }}
                    </span>
                    @if($notification->read_at)
                    <span>
                        <i class="fas fa-check-circle mr-1"></i>
                        Dibaca: {{ $notification->read_at->format('d M Y H:i') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('notifications.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors">
            Kembali
        </a>
        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-trash mr-2"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection