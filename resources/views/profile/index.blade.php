@extends('layouts.app') {{-- ini file layout utama yang sudah ada sidebar & headbar --}}

@section('title', 'Profil Saya')

@section('content')
<div class="container mx-auto p-6">

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
        <div class="max-w-4xl mx-auto">
          <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Profil</h1>
          
          <!-- Profile Tabs -->
          <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
              <nav class="flex -mb-px">
                <button class="py-2 px-4 border-b-2 border-blue-500 font-medium text-blue-600" data-tab="profile">
                  Informasi Profil
                </button>
                <button class="py-2 px-4 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700" data-tab="password">
                  Ubah Password
                </button>
                <button class="py-2 px-4 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700" data-tab="settings">
                  Pengaturan
                </button>
              </nav>
            </div>
            
            <!-- Profile Tab Content -->
            <div class="p-6">
              <!-- Profile Form -->
              <div id="profile-tab" class="tab-content">
                <form action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                      <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                      <input type="email" name="email" value="{{ $user->email }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                      <input type="text" name="phone" value="{{ $profile->phone ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                      <input type="date" name="birth_date" value="{{ $profile->birth_date ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                      <input type="text" name="place_of_birth" value="{{ $profile->place_of_birth ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                      <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih</option>
                        <option value="L" {{ $user->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $user->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan</label>
                      <input type="text" name="education" value="{{ $user->education ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Keahlian</label>
                      <input type="text" name="skill" value="{{ $user->skill ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                      <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ $user->address ?? '' }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                      <textarea name="bio" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ $user->bio ?? '' }}</textarea>
                    </div>
                  </div>
                  
                  <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                      Simpan Perubahan
                    </button>
                  </div>
                </form>
              </div>
              
              <!-- Password Tab Content -->
              <div id="password-tab" class="tab-content hidden">
                <form action="{{ route('profile.update-password') }}" method="POST">
                  @csrf
                  <div class="max-w-md">
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                      <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                      <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                      <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mt-6">
                      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Simpan Password
                      </button>
                    </div>
                  </div>
                </form>
              </div>
              
              <!-- Settings Tab Content -->
              <div id="settings-tab" class="tab-content hidden">
                <form action="{{ route('profile.updateSettings') }}" method="POST">
                  @csrf
                  <div class="max-w-md">
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Tema</label>
                      <select name="theme" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="light" {{ $settings->theme == 'light' ? 'selected' : '' }}>Terang</option>
                        <option value="dark" {{ $settings->theme == 'dark' ? 'selected' : '' }}>Gelap</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa</label>
                      <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="id" {{ $settings->language == 'id' ? 'selected' : '' }}>Indonesia</option>
                        <option value="en" {{ $settings->language == 'en' ? 'selected' : '' }}>English</option>
                      </select>
                    </div>
                    <div class="mb-4">
                      <label class="flex items-center">
                        <input type="checkbox" name="email_notifications" value="1" {{ $settings->email_notifications ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm text-gray-700">Terima notifikasi email</span>
                      </label>
                    </div>
                    <div class="mb-4">
                      <label class="flex items-center">
                        <input type="checkbox" name="chat_notifications" value="1" {{ $settings->chat_notifications ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm text-gray-700">Terima notifikasi chat</span>
                      </label>
                    </div>
                    
                    <div class="mt-6">
                      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Simpan Pengaturan
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
      const tabButtons = document.querySelectorAll('[data-tab]');
      const tabContents = document.querySelectorAll('.tab-content');
      
      tabButtons.forEach(button => {
        button.addEventListener('click', function() {
          const tabName = this.getAttribute('data-tab');
          
          // Remove active class from all buttons and contents
          tabButtons.forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
          });
          
          tabContents.forEach(content => {
            content.classList.add('hidden');
          });
          
          // Add active class to clicked button and corresponding content
          this.classList.remove('border-transparent', 'text-gray-500');
          this.classList.add('border-blue-500', 'text-blue-600');
          
          document.getElementById(tabName + '-tab').classList.remove('hidden');
        });
      });
    });
  </script>
</body>
</html>

@endsection