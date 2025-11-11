{{-- Memperluas layout master --}}
@extends('layouts.app')

{{-- Mengisi bagian title di head --}}
@section('title', 'Pengaturan Role & Jabatan - SIAS Soeradji')

{{-- Mengisi bagian title di header --}}
@section('header-title', 'Pengaturan Role & Jabatan')
@section('header-subtitle', 'Kelola pengguna, role, dan jabatan sistem')

{{-- Mengisi konten utama --}}
@section('content')
<div class="max-w-7xl mx-auto pr-4 lg:pr-20 transition-all duration-300">
    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Card untuk Form Tambah/Edit User -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 ml-2 lg:ml-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru
        </h2>
        <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">NIP</label>
                <input type="text" name="nip" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan</label>
                <input type="text" name="jabatan" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                <select name="role" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="admin">Admin</option>
                    <option value="tata usaha">Tata Usaha</option>
                    <option value="arsiparis">Arsiparis/Petugas Arsip</option>
                    <option value="pejabat">Pejabat Struktural</option>
                    <option value="kepegawaian">Kepegawaian</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                <input type="text" name="username" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" name="password" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status Akun</label>
                <select name="status" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition duration-200">
                    <i class="fas fa-save mr-2"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>

    <!-- Card untuk Daftar Pengguna -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Daftar Pengguna</h2>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
            </div>
        </div>
        
        <!-- Form Pencarian -->
        <div class="mb-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ $search }}" 
                       placeholder="Cari berdasarkan nama, username, email, atau role..." 
                       class="flex-1 p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">
                        <i class="fas fa-times mr-2"></i> Reset
                    </a>
                @endif
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $user->nama_lengkap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($user->role) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button onclick="openEditModal({{ $user->id }})" class="text-blue-600 hover:text-blue-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-toggle-{{ $user->status === 'aktif' ? 'on' : 'off' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.duplicate', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin?')">
                                    @csrf
                                    <button type="submit" class="text-purple-600 hover:text-purple-900">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
                <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div x-data="{ 
    editModalOpen: false, 
    editUser: {
        id: null,
        nama_lengkap: '',
        nip: '',
        jabatan: '',
        email: '',
        role: '',
        username: '',
        status: 'aktif'
    },
    loading: false
}" style="display: none;">
    <div x-show="editModalOpen" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         @click.self="editModalOpen = false">
        
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800"
             x-show="editModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90">
            
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white leading-6">
                    <i class="fas fa-user-edit mr-2"></i> Edit Pengguna
                </h3>
                <div class="mt-4">
                    <form x-bind:action="`/admin/users/${editUser.id}`" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4" onsubmit="event.preventDefault(); submitEditForm(event)">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" x-model="editUser.nama_lengkap" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">NIP</label>
                            <input type="text" name="nip" x-model="editUser.nip" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" x-model="editUser.email" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan</label>
                            <input type="text" name="jabatan" x-model="editUser.jabatan" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                            <select name="role" x-model="editUser.role" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="admin">Admin</option>
                                <option value="tata usaha">Tata Usaha</option>
                                <option value="arsiparis">Arsiparis/Petugas Arsip</option>
                                <option value="pejabat">Pejabat Struktural</option>
                                <option value="kepegawaian">Kepegawaian</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                            <input type="text" name="username" x-model="editUser.username" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status Akun</label>
                            <select name="status" x-model="editUser.status" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2 flex justify-end gap-2 mt-4">
                            <button type="button" @click="editModalOpen = false" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">Batal</button>
                            <button type="submit" :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 disabled:opacity-50">
                                <span x-show="!loading"><i class="fas fa-save mr-2"></i>Perbarui</span>
                                <span x-show="loading"><i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openEditModal(userId) {
    // Ambil referensi ke komponen Alpine.js
    const modalComponent = Alpine.$data(document.querySelector('[x-data]'));

    // Set loading state
    modalComponent.loading = true;

    // Fetch data user dari server
    fetch(`/admin/users/${userId}/edit`)
        .then(response => response.json())
        .then(data => {
            // Isi data ke dalam form
            modalComponent.editUser = {
                id: data.id,
                nama_lengkap: data.nama_lengkap,
                nip: data.nip || '',
                jabatan: data.jabatan || '',
                email: data.email,
                role: data.role,
                username: data.username,
                status: data.status
            };
            // Buka modal
            modalComponent.editModalOpen = true;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data user.');
        })
        .finally(() => {
            // Hapus loading state
            modalComponent.loading = false;
        });
}

function submitEditForm(event) {
    const form = event.target;
    const modalComponent = Alpine.$data(form.closest('[x-data]'));
    const formData = new FormData(form);

    modalComponent.loading = true;

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            // Jika server mengembalikan error (misalnya validasi gagal)
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        // Asumsikan controller redirect dengan success message
        // Kita reload halaman untuk melihat perubahan
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        // Tampilkan error validasi jika ada
        if (error.errors) {
            let errorMessage = 'Perbaiki kesalahan berikut:\n';
            for (const key in error.errors) {
                errorMessage += `- ${error.errors[key][0]}\n`;
            }
            alert(errorMessage);
        } else {
            alert('Terjadi kesalahan saat memperbarui user.');
        }
    })
    .finally(() => {
        modalComponent.loading = false;
    });
}
</script>
@endsection