<!-- resources/views/partials/navigation.blade.php -->
<!-- Header -->
<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo dan Nama Aplikasi -->
            <div class="flex items-center">
                <button @click="toggleSidebar()" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex-shrink-0 flex items-center ml-4 lg:ml-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('img/logo-soeradji.png') }}" alt="SIAS Soeradji">
                        <span class="ml-2 text-xl font-bold text-gray-900">SIAS 2025</span>
                    </a>
                </div>
            </div>
            
            <!-- Menu Navigasi Desktop -->
            <nav class="hidden lg:flex lg:items-center lg:space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-home mr-1"></i> Beranda
                </a>
                
                <!-- Dropdown Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center transition-colors duration-200">
                        <i class="fas fa-folder-plus mr-1"></i> Arsip
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                        <div class="py-1">
                            <a href="{{ route('arsip.aktif.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-folder-open mr-2"></i> Arsip Aktif
                            </a>
                            <a href="{{ route('arsip.inaktif.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-archive mr-2"></i> Arsip Inaktif
                            </a>
                            <a href="{{ route('arsip.vital.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-shield-alt mr-2"></i> Arsip Vital
                            </a>
                            <a href="{{ route('arsip.alihmedia.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-exchange-alt mr-2"></i> Arsip Alih Media
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center transition-colors duration-200">
                        <i class="fas fa-envelope-open-text mr-1"></i> Surat Tugas
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                        <div class="py-1">
                            <a href="{{ route('surat_tugas.form') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-file-alt mr-2"></i> Buat Surat Tugas
                            </a>
                            <a href="{{ route('log_surat_tugas.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-history mr-2"></i> Log Surat Tugas
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center transition-colors duration-200">
                        <i class="fas fa-stamp mr-1"></i> Watermark
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                        <div class="py-1">
                            <a href="{{ route('watermark.image.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 bg-blue-50">
                                <i class="fas fa-image mr-2"></i> Watermark Gambar
                            </a>
                            <a href="{{ route('watermark.text.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-file-alt mr-2"></i> Watermark Dokumen
                            </a>
                            <a href="{{ route('watermark.logs.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-history mr-2"></i> Log Watermark
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center transition-colors duration-200">
                        <i class="fas fa-cog mr-1"></i> Pengaturan
                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                        <div class="py-1">
                            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-users-cog mr-2"></i> Role & Jabatan
                            </a>
                            <a href="{{ route('admin.pengaturan.background') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-image mr-2"></i> Pengaturan Background
                            </a>
                            <a href="{{ route('admin.pengaturan.nomor') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-hashtag mr-2"></i> Pengaturan Nomor Arsip
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Menu User -->
            <div class="flex items-center space-x-4">
                <!-- Notifikasi -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-1 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <div class="py-2 px-4 border-b border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900">Notifikasi</h3>
                        </div>
                        <div class="max-h-60 overflow-y-auto">
                            <div class="py-2 px-4 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="h-2 w-2 rounded-full bg-blue-500"></div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Pemberitahuan Sistem</p>
                                        <p class="text-xs text-gray-500">Sistem akan melakukan maintenance malam ini</p>
                                    </div>
                                </div>
                            </div>
                            <div class="py-2 px-4 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="h-2 w-2 rounded-full bg-green-500"></div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Tugas Baru</p>
                                        <p class="text-xs text-gray-500">Anda memiliki tugas baru yang perlu diselesaikan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="py-2 px-4 border-t border-gray-200">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat semua notifikasi</a>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        @if(!empty($user->profile->foto))
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/profiles/' . $user->profile->foto) }}" alt="{{ $user->nama_lengkap }}">
                        @else
                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                <span class="text-white font-medium">{{ $user->initials }}</span>
                            </div>
                        @endif
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100">
                        <div class="py-1">
                            <div class="px-4 py-2">
                                <p class="text-sm font-medium text-gray-900">{{ $user->nama_lengkap }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($user->role) }}</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-user mr-2"></i> Profil Saya
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-cog mr-2"></i> Pengaturan
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Sidebar Mobile -->
<div class="lg:hidden fixed inset-0 z-50 hidden" id="mobile-sidebar">
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="$el.parentElement.classList.add('hidden')"></div>
    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="$el.parentElement.parentElement.classList.add('hidden')">
                <i class="fas fa-times text-white"></i>
            </button>
        </div>
        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            <div class="flex-shrink-0 flex items-center px-4">
                <img class="h-8 w-auto" src="{{ asset('img/logo-soeradji.png') }}" alt="SIAS Soeradji">
                <span class="ml-2 text-xl font-bold text-gray-900">SIAS 2025</span>
            </div>
            <nav class="mt-5 px-2 space-y-1">
                <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-100 transition-colors duration-200">
                    <i class="fas fa-home mr-3"></i> Beranda
                </a>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="group w-full flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-folder-plus mr-3"></i> Arsip
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </button>
                    
                    <div x-show="open" class="mt-1 space-y-1">
                        <a href="{{ route('arsip.aktif.create') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-folder-open mr-2"></i> Arsip Aktif
                        </a>
                        <a href="{{ route('arsip.inaktif.create') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-archive mr-2"></i> Arsip Inaktif
                        </a>
                        <a href="{{ route('arsip.vital.create') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-shield-alt mr-2"></i> Arsip Vital
                        </a>
                        <a href="{{ route('arsip.alihmedia.create') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-exchange-alt mr-2"></i> Arsip Alih Media
                        </a>
                    </div>
                </div>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="group w-full flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-envelope-open-text mr-3"></i> Surat Tugas
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </button>
                    
                    <div x-show="open" class="mt-1 space-y-1">
                        <a href="{{ route('surat_tugas.form') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-file-alt mr-2"></i> Buat Surat Tugas
                        </a>
                        <a href="{{ route('log_surat_tugas.index') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-history mr-2"></i> Log Surat Tugas
                        </a>
                    </div>
                </div>
                
                <div class="relative" x-data="{ open: true }">
                    <button @click="open = !open" class="group w-full flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-stamp mr-3"></i> Watermark
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </button>
                    
                    <div x-show="open" class="mt-1 space-y-1">
                        <a href="{{ route('watermark.image.index') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200 bg-blue-50">
                            <i class="fas fa-image mr-2"></i> Watermark Gambar
                        </a>
                        <a href="{{ route('watermark.text.index') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-file-alt mr-2"></i> Watermark Dokumen
                        </a>
                        <a href="{{ route('watermark.logs.index') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-history mr-2"></i> Log Watermark
                        </a>
                    </div>
                </div>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="group w-full flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-900 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fas fa-cog mr-3"></i> Pengaturan
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </button>
                    
                    <div x-show="open" class="mt-1 space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-users-cog mr-2"></i> Role & Jabatan
                        </a>
                        <a href="{{ route('admin.pengaturan.background') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-image mr-2"></i> Pengaturan Background
                        </a>
                        <a href="{{ route('admin.pengaturan.nomor') }}" class="group flex items-center pl-10 pr-2 py-2 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-hashtag mr-2"></i> Pengaturan Nomor Arsip
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
            <a href="{{ route('profile.index') }}" class="flex-shrink-0 w-full group block">
                <div class="flex items-center">
                    <div>
                        @if(!empty($user->profile->foto))
                            <img class="inline-block h-9 w-9 rounded-full" src="{{ asset('storage/profiles/' . $user->profile->foto) }}" alt="{{ $user->nama_lengkap }}">
                        @else
                            <div class="inline-block h-9 w-9 rounded-full bg-blue-500 flex items-center justify-center">
                                <span class="text-white font-medium">{{ $user->initials }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ $user->nama_lengkap }}</p>
                        <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>
            </a>
            <div class="mt-3 flex-shrink-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk Navigation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle mobile sidebar
    const toggleSidebarBtn = document.querySelector('[x-data*="toggleSidebar"]');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    
    if (toggleSidebarBtn && mobileSidebar) {
        toggleSidebarBtn.addEventListener('click', function() {
            mobileSidebar.classList.toggle('hidden');
        });
    }
});
</script>