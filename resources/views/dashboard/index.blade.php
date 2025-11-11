@extends('layouts.app')

@section('title', 'Dashboard - SIAS Soeradji')

@section('content')
<div class="container-fluid px-4 py-4" style="min-height: 100vh; padding-bottom: 5rem;">
<!-- Welcome Card -->
<div class="welcome-card">
  <div class="flex items-center justify-between">
    <div>
      <h2>Selamat datang kembali, {{ auth()->user()->nama_lengkap }}! 👋</h2>
      <p>Sistem Informasi Arsip Soeradji - {{ date('d F Y') }}</p>
      
      <div class="stats">
        <div class="stat-item">
          <div class="stat-label">Login sebagai</div>
          <div class="stat-value">{{ ucfirst(auth()->user()->role) }}</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">Jabatan</div>
          <div class="stat-value">{{ auth()->user()->jabatan }}</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">Status</div>
          <div class="stat-value">
            <span class="inline-flex items-center gap-1">
              <span class="w-2 h-2 bg-green-400 rounded-full"></span>
              Online
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="hidden lg:block">
      <img src="{{ asset('img/welcome-illustration.svg') }}" alt="Welcome" class="h-40">
    </div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
  <!-- Stats Cards -->
  <div class="card">
    <div class="flex items-center">
      <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
        <i class="fas fa-folder-open text-blue-600 text-xl"></i>
      </div>
      <div class="ml-4">
        <p class="text-sm font-medium text-gray-500">Arsip Aktif</p>
        <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_arsip_aktif || '0'"></p>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="flex items-center">
      <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
        <i class="fas fa-archive text-green-600 text-xl"></i>
      </div>
      <div class="ml-4">
        <p class="text-sm font-medium text-gray-500">Arsip Inaktif</p>
        <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_arsip_inaktif || '0'"></p>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="flex items-center">
      <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
        <i class="fas fa-file-signature text-purple-600 text-xl"></i>
      </div>
      <div class="ml-4">
        <p class="text-sm font-medium text-gray-500">Surat Tugas</p>
        <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_surat_tugas || '0'"></p>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="flex items-center">
      <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
        <i class="fas fa-file-alt text-red-600 text-xl"></i>
      </div>
      <div class="ml-4">
        <p class="text-sm font-medium text-gray-500">Berita Acara</p>
        <p class="text-2xl font-semibold text-gray-900" x-text="(stats.total_berita_pemindahan + stats.total_berita_pemusnahan + stats.total_berita_alihmedia) || '0'"></p>
      </div>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- Recent Activities -->
  <div class="lg:col-span-2 card">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-medium text-gray-900">Aktivitas Terkini</h3>
      <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
    </div>
    <div class="space-y-4">
      <template x-for="activity in activities" :key="activity.id">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <img :src="activity.user.avatar || '/img/default-avatar.png'" alt="User" class="h-8 w-8 rounded-full">
          </div>
          <div class="ml-3">
            <p class="text-sm text-gray-900">
              <span class="font-medium" x-text="activity.user.name"></span>
              <span x-text="activity.description"></span>
            </p>
            <p class="text-xs text-gray-500" x-text="activity.time"></p>
          </div>
        </div>
      </template>
      <div x-show="activities.length === 0" class="text-center py-4 text-gray-500">
        <p>Belum ada aktivitas terkini</p>
      </div>
    </div>
  </div>
  
  <!-- Quick Actions -->
  <div class="card">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
    <div class="space-y-3">
      @if(in_array(auth()->user()->role, ['admin', 'arsiparis', 'pejabat_struktural']))
      <a href="{{ route('arsip.aktif.create') }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" @click="navigateToPage(event, '{{ route('arsip.aktif.create') }}')">
        <i class="fas fa-plus mr-2"></i>
        Tambah Arsip Baru
      </a>
      
      <a href="{{ route('surat_tugas.form') }}" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700" @click="navigateToPage(event, '{{ route('surat_tugas.form') }}')">
        <i class="fas fa-file-signature mr-2"></i>
        Buat Surat Tugas
      </a>
      
      <a href="#" class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
        <i class="fas fa-file-alt mr-2"></i>
        Buat Berita Acara
      </a>
      @endif
      
      <a href="#" class="w-full flex items-center justify-center px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
        <i class="fas fa-print mr-2"></i>
        Cetak Laporan
      </a>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
  <div class="card">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Arsip</h3>
    <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
      <canvas id="archiveChart"></canvas>
    </div>
  </div>
  
  <div class="card">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Aktivitas Bulanan</h3>
    <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
      <canvas id="activityChart"></canvas>
    </div>
  </div>
</div>

<script>
  // Initialize dashboard data
  document.addEventListener('alpine:init', () => {
    Alpine.store('dashboard', {
      stats: {
        total_arsip_aktif: 0,
        total_arsip_inaktif: 0,
        total_surat_tugas: 0,
        total_berita_pemindahan: 0,
        total_berita_pemusnahan: 0,
        total_berita_alihmedia: 0
      },
      activities: [],
      
      init() {
        this.loadStats();
        this.loadActivities();
        this.initCharts();
      },
      
      loadStats() {
        fetch('/api/dashboard/stats')
          .then(response => response.json())
          .then(data => {
            this.stats = data;
          })
          .catch(error => {
            console.error('Error loading stats:', error);
          });
      },
      
      loadActivities() {
        fetch('/api/dashboard/activities')
          .then(response => response.json())
          .then(data => {
            this.activities = data;
          })
          .catch(error => {
            console.error('Error loading activities:', error);
          });
      },
      
      initCharts() {
        // Initialize archive chart
        const archiveCtx = document.getElementById('archiveChart').getContext('2d');
        new Chart(archiveCtx, {
          type: 'doughnut',
          data: {
            labels: ['Arsip Aktif', 'Arsip Inaktif', 'Arsip Vital', 'Arsip Alih Media'],
            datasets: [{
              data: [
                this.stats.total_arsip_aktif,
                this.stats.total_arsip_inaktif,
                this.stats.total_arsip_vital,
                this.stats.total_arsip_alihmedia
              ],
              backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(251, 146, 60, 0.8)'
              ],
              borderWidth: 0
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom'
              }
            }
          }
        });
        
        // Initialize activity chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        new Chart(activityCtx, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
              label: 'Arsip Ditambahkan',
              data: [12, 19, 3, 5, 2, 3],
              backgroundColor: 'rgba(59, 130, 246, 0.8)'
            }, {
              label: 'Surat Tugas Dibuat',
              data: [7, 11, 5, 8, 3, 7],
              backgroundColor: 'rgba(16, 185, 129, 0.8)'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      }
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    function detectZoom() {
        const zoomLevel = window.outerWidth / window.innerWidth;
        if (zoomLevel < 0.9) { // Jika zoom out lebih dari 90%
            document.body.style.minHeight = (window.innerHeight * 1.5) + 'px';
        } else {
            document.body.style.minHeight = '100vh';
        }
    }
    
    window.addEventListener('resize', detectZoom);
    detectZoom();
});

</script>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection