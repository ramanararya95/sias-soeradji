@extends('layouts.app')

@section('title', 'Registrasi Arsip Inaktif')

@section('content')
<div class="main-container">
    <main class="content-area px-6 pt-2 pb-0 space-y-4">
        <div class="bg-white rounded-xl p-6 shadow-md max-w-5xl mx-auto w-full">
            <h1 class="text-xl font-semibold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fas fa-archive text-blue-600"></i>
                Registrasi Arsip Inaktif
            </h1>

            <!-- Tampilkan inputan terakhir -->
            @if ($lastEntry)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Inputan Terakhir</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ $lastEntry->uraian_isi }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Belum Ada Data</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Belum ada data arsip inaktif yang terinput sebelumnya.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <p class="text-sm text-slate-600 mb-4">Isi field-field dibawah ini sesuai identifikasi pada arsip yang akan diinput.</p>

            @if (session('status') == 'sukses')
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                Arsip berhasil disimpan!
            </div>
            @endif

            <form action="{{ route('arsip.inaktif.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Arsip</label>
                    <input type="text" name="nomor_arsip" value="{{ $nomorArsip }}" readonly
                        class="w-full px-4 py-2 border border-slate-300 rounded bg-gray-100 text-slate-700">
                </div>

                <div class="mt-2">
                    <button type="button" onclick="regenerateNomorArsip()" class="text-blue-600 text-sm hover:underline">
                        🔄 Generate Ulang Nomor Arsip
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kode Klasifikasi</label>
                    <input type="text" name="kode_ka" required
                        placeholder="diisi dengan kode klasifikasi"
                        class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Uraian Isi Informasi</label>
                    <textarea name="uraian_isi" rows="5"
                        placeholder="diisi dengan isi informasi pokok masalah arsip"
                        class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
                    <input type="text" name="tahun" required
                        placeholder="diisi tanggal bulan tahun"
                        class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Volume</label>
                    <input type="text" name="volume" required
                        placeholder="diisi jumlah berkas. contoh: 1 berkas dll"
                        class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" required
                        placeholder="diisi kondisi arsip. contoh: baik dll"
                        class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Unggah File (PDF/JPG)</label>
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-2 border border-slate-300 rounded bg-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <p class="text-xs text-slate-500 mt-1 italic">Opsional. Biarkan kosong jika tidak ada file.</p>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded shadow">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
document.querySelector("form").addEventListener("submit", async function(e) {
    const nomorInput = document.querySelector('input[name="nomor_arsip"]');
    const nomor = nomorInput.value;

    try {
        const res = await fetch("{{ route('api.cek_nomor_arsip_inaktif') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: "nomor=" + encodeURIComponent(nomor)
        });

        const data = await res.json();

        if (data.exists) {
            e.preventDefault();
            alert("❗ Nomor arsip sudah digunakan. Silakan klik Generate Ulang atau muat ulang halaman.");
        }
    } catch (error) {
        console.error("Gagal cek nomor arsip:", error);
    }
});

function regenerateNomorArsip() {
    location.reload();
}
</script>
@endsection