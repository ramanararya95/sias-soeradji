<!-- resources/views/notifications/index.blade.php -->
@extends('layouts.app')

@section('title', 'Notifikasi - SIAS Soeradji')

@section('header-title', 'Notifikasi')
@section('header-subtitle', 'Daftar Notifikasi Anda')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Notifikasi</h2>
        <div class="flex space-x-2">
            @if(auth()->user()->notifications()->whereNull('read_at')->count() > 0)
            <button onclick="markAllAsRead()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-check-double mr-2"></i> Tandai Semua Dibaca
            </button>
            @endif
        </div>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="space-y-3">
        @forelse($notifications as $notification)
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ !$notification->read_at ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        @if($notification->type == 'info')
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-info text-blue-500"></i>
                        </div>
                        @elseif($notification->type == 'success')
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                        @elseif($notification->type == 'warning')
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation text-yellow-500"></i>
                        </div>
                        @elseif($notification->type == 'error')
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-times text-red-500"></i>
                        </div>
                        @else
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-bell text-gray-500"></i>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $notification->title }}
                            </h3>
                            @if(!$notification->read_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Baru
                            </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ $notification->message }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                            {{ $notification->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('notifications.show', $notification->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat Detail
                    </a>
                    @if(!$notification->read_at)
                    <button onclick="markAsRead({{ $notification->id }})" class="text-green-600 hover:text-green-800 text-sm">
                        Tandai Dibaca
                    </button>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <i class="fas fa-bell-slash text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-500 dark:text-gray-400">Tidak ada notifikasi</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>

<script>
function markAsRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection