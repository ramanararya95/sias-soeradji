<!-- resources/views/laporan/arsip/result.blade.php -->
<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
            Hasil Laporan Arsip {{ ucfirst($jenisArsip) }}
        </h3>
        <div class="flex space-x-2">
            <a href="{{ route('laporan.arsip.pdf', request()->query()) }}" 
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
            </a>
            <a href="{{ route('laporan.arsip.excel', request()->query()) }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
        </div>
    </div>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nomor Arsip</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul/Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Dibuat</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pembuat</th>
                
                @if($jenisArsip === 'aktif' || $jenisArsip === 'inaktif' || $jenisArsip === 'vital')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kurun Waktu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tingkat Perkembangan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lokasi Simpan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi Fisik</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Media Arsip</th>
                @elseif($jenisArsip === 'alihmedia')
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Media Asal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Media Tujuan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lokasi Simpan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi Fisik</th>
                @endif
                
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($arsip as $index => $item)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->nomor ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $item->judul ?? $item->nama ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->user->nama_lengkap }}</td>
                
                @if($jenisArsip === 'aktif' || $jenisArsip === 'inaktif' || $jenisArsip === 'vital')
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->kurun_waktu ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->tingkat_perkembangan ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->jumlah ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->lokasi_simpan ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->deskripsi_fisik ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->media_arsip ?? '-' }}</td>
                @elseif($jenisArsip === 'alihmedia')
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->media_asal ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->media_tujuan ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->jumlah ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->lokasi_simpan ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->deskripsi_fisik ?? '-' }}</td>
                @endif
                
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($item->status == 'draft') bg-yellow-100 text-yellow-800 
                        @elseif($item->status == 'approved') bg-green-100 text-green-800 
                        @elseif($item->status == 'rejected') bg-red-100 text-red-800 
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($item->status ?? '-') }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada data arsip yang ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
    <p>Total: {{ $arsip->count() }} data</p>
</div>